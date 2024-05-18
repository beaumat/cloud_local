<?php

namespace App\Services;

use App\Models\Check;
use App\Models\CheckBills;
use Illuminate\Support\Facades\DB;

class BillPaymentServices
{
    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
    ) {
        $this->object = $objectService;
        $this->compute = $computeServices;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        return Check::where('ID', $ID)->where('TYPE', 1)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $BANK_ACCOUNT_ID,
        int $PAY_TO_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        string $NOTES,
        int $ACCOUNTS_PAYABLE_ID
    ): int {

        $ID = (int) $this->object->ObjectNextID('CHECK');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('CHECK');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Check::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'TYPE' => 1,
            'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
            'PAY_TO_ID' => $PAY_TO_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'AMOUNT' => $AMOUNT,
            'NOTES' => $NOTES,
            'PRINTED' => false,
            'STATUS' => 2,
            'STATUS_DATE' => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID

        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $BANK_ACCOUNT_ID,
        int $PAY_TO_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        string $NOTES
    ) {
        Check::where('ID', $ID)
            ->where('TYPE', 1)
            ->update([
                'ID' => $ID,
                'RECORDED_ON' => $this->dateServices->Now(),
                'CODE' => $CODE,
                'BANK_ACCOUNT_ID' => $BANK_ACCOUNT_ID,
                'PAY_TO_ID' => $PAY_TO_ID,
                'LOCATION_ID' => $LOCATION_ID,
                'AMOUNT' => $AMOUNT,
                'NOTES' => $NOTES,
                'PRINTED' => false
            ]);
    }
    public function UpdateBillPaymentApplied(int $CHECK_ID): float
    {
        $pay = CheckBills::query()
            ->select(DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID), 0) as pay'))
            ->where('check_bills.CHECK_ID', '=', $CHECK_ID)
            ->first()
            ->pay;

        return $pay;
    }
    public function Delete(int $ID)
    {
        Check::where('ID', $ID)->where('TYPE', 1)->delete();
    }
    public function Search($search, $locationId, $perPage)
    {
        return Check::query()
            ->select([
                'check.ID',
                'check.CODE',
                'check.DATE',
                'check.AMOUNT',
                'check.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as BANK_ACCOUNT_NAME'
            ])
            ->join('contact as c', 'c.ID', '=', 'check.PAY_TO_ID')
            ->join('account as a', 'a.ID', '=', 'check.BANK_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'check.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'check.STATUS')
            ->where('check.TYPE', 1)
            ->when($search, function ($query) use (&$search) {
                $query->where('check.CODE', 'like', '%' . $search . '%')
                    ->orWhere('check.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('check.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('check.ID', 'desc')
            ->paginate($perPage);

    }
    public function BillPaymentBillsExist(int $CHECK_ID, int $BILL_ID)
    {
        $data = CheckBills::where('CHECK_ID', $CHECK_ID)->where('BILL_ID', $BILL_ID)->first();
        if ($data) {
            return $data->ID;
        }
        return 0;
    }

    public function billPaymentBills(int $CHECK_ID)
    {
        return CheckBills::query()
            ->select([
                'check_bills.ID',
                'check_bills.BILL_ID',
                'check_bills.DISCOUNT',
                'check_bills.AMOUNT_PAID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE'
            ])
            ->join('bill', 'bill.ID', '=', 'check_bills.BILL_ID')
            ->where('check_bills.CHECK_ID', $CHECK_ID)
            ->get();

    }
    public function getTotalApplied(int $CHECK_ID): float
    {
        if ($CHECK_ID == 0) {
            return 0;
        }

        $result = CheckBills::query()
            ->select([
                DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID),0) as PAID')
            ])
            ->where('check_bills.CHECK_ID', $CHECK_ID)
            ->first();

        return (float) $result->PAID;
    }
    public function billPaymentBills_Delete(int $ID, int $CHECK_ID, int $BILL_ID)
    {
        CheckBills::where('ID', $ID)->where('CHECK_ID', $CHECK_ID)->where('BILL_ID', $BILL_ID)->delete();
    }
    public function billPaymentBills_Store(
        int $CHECK_ID,
        int $BILL_ID,
        float $DISCOUNT,
        float $AMOUNT_PAID,
        int $DISCOUNT_ACCOUNT_ID,
        int $ACCOUNTS_PAYABLE_ID
    ) {

        $ID = $this->object->ObjectNextID('CHECK_BILLS');
        CheckBills::create([
            'ID' => $ID,
            'CHECK_ID' => $CHECK_ID,
            'BILL_ID' => $BILL_ID,
            'DISCOUNT' => $DISCOUNT,
            'AMOUNT_PAID' => $AMOUNT_PAID,
            'DISCOUNT_ACCOUNT_ID' => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null
        ]);

    }
    public function getTotalPay(int $BILL_ID, int $EXECPT_CHECK_ID): float
    {
        $data = CheckBills::query()
            ->selectRaw('ifnull(sum(AMOUNT_PAID),0) as total')
            ->where('BILL_ID', $BILL_ID)
            ->where('CHECK_ID', '<>', $EXECPT_CHECK_ID)
            ->first();

        if ($data) {
            return $data->total;
        }

        return 0;

    }
    public function billPaymentBills_Update(
        int $ID,
        int $CHECK_ID,
        int $BILL_ID,
        float $DISCOUNT,
        float $AMOUNT_PAID

    ) {
        CheckBills::where('ID', $ID)
            ->where('CHECK_ID', $CHECK_ID)
            ->where('BILL_ID', $BILL_ID)
            ->update([
                'DISCOUNT' => $DISCOUNT,
                'AMOUNT_PAID' => $AMOUNT_PAID
            ]);

    }
}