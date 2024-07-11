<?php

namespace App\Services;

use App\Models\PatientPaymentCharges;
use App\Models\PatientPayments;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class PatientPaymentServices
{

    use WithPagination;
    private $object;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectService, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {
        $this->object = $objectService;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function get($ID)
    {
        return PatientPayments::where('ID', $ID)->first();
    }
    public function PaymentHaveAvailable(int $PATIENT_ID)
    {
        $data = PatientPayments::where('PATIENT_ID', $PATIENT_ID)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->first();

        return $data;
    }
    public function Store(
        string $CODE,
        $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        float $AMOUNT_APPLIED,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID

    ): int {

        $ID = (int) $this->object->ObjectNextID('PATIENT_PAYMENT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PATIENT_PAYMENT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        PatientPayments::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'PATIENT_ID' => $PATIENT_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'AMOUNT' => $AMOUNT,
            'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            'PAYMENT_METHOD_ID' => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CARD_NO' => $CARD_NO,
            'CARD_EXPIRY_DATE' => $CARD_EXPIRY_DATE ?? null,
            'RECEIPT_REF_NO' => $RECEIPT_REF_NO,
            'RECEIPT_DATE' => $RECEIPT_DATE ?? null,
            'NOTES' => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
            'OVERPAYMENT_ACCOUNT_ID' => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
            'STATUS' => 2,
            'STATUS_DATE' => $this->dateServices->NowDate(),
            'DEPOSITED' => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID
        ]);
        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        $DATE,
        int $PATIENT_ID,
        int $LOCATION_ID,
        float $AMOUNT,
        int $PAYMENT_METHOD_ID,
        string $CARD_NO,
        $CARD_EXPIRY_DATE,
        string $RECEIPT_REF_NO,
        $RECEIPT_DATE,
        string $NOTES,
        int $UNDEPOSITED_FUNDS_ACCOUNT_ID,
        int $OVERPAYMENT_ACCOUNT_ID,
        bool $DEPOSITED,
        int $ACCOUNTS_RECEIVABLE_ID
    ) {
        PatientPayments::where('ID', $ID)->update([
            'CODE'              => $CODE,
            'DATE'              => $DATE,
            'PATIENT_ID'        => $PATIENT_ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'AMOUNT'            => $AMOUNT,
            'PAYMENT_METHOD_ID' => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CARD_NO'           => $CARD_NO,
            'CARD_EXPIRY_DATE'  => $CARD_EXPIRY_DATE ?? null,
            'RECEIPT_REF_NO'    => $RECEIPT_REF_NO,
            'RECEIPT_DATE'      => $RECEIPT_DATE ?? null,
            'NOTES'             => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
            'OVERPAYMENT_ACCOUNT_ID' => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
            'DEPOSITED'         => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID
        ]);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        PatientPayments::where('ID', $ID)->update([
            'STATUS' => $STATUS,
            'STATUS_DATE' => $this->dateServices->NowDate()
        ]);
    }
    public function ConfirmProccess(int $ID)
    {
        PatientPayments::where('ID', $ID)->update([
            'IS_CONFIRM' => true,
            'DATE_CONFIRM' => $this->dateServices->NowDate()
        ]);
    }
    public function UnConfirmProccess(int $ID)
    {
        PatientPayments::where('ID', $ID)->update([
            'IS_CONFIRM' => false,
            'DATE_CONFIRM' => null
        ]);
    }
    public function Delete(int $ID)
    {
        PatientPaymentCharges::where('PATIENT_PAYMENT_ID', $ID)->delete();
        PatientPayments::where('ID', $ID)->delete();
    }
    public function Search($search, int $locationId, int $perPage)
    {
        return PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
                'patient_payment.NOTES',
                 DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'pm.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.FILE_PATH',
                'patient_payment.IS_CONFIRM'

            ])
            ->join('contact as c', 'c.ID', '=', 'patient_payment.PATIENT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'patient_payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'patient_payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('patient_payment.CODE', 'like', '%' . $search . '%')
                    ->orWhere('patient_payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                    ->orWhere('patient_payment.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('patient_payment.ID', 'desc')
            ->paginate($perPage);
    }


    public function PaymentChargeStore(int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID, float $DISCOUNT, float $AMOUNT_APPLIED, int $DISCOUNT_ACCOUNT_ID, int $ACCOUNTS_RECEIVABLE_ID): int
    {

        $ID = $this->object->ObjectNextID('PATIENT_PAYMENT_CHARGES');
        PatientPaymentCharges::create([
            'ID'                        => $ID,
            'PATIENT_PAYMENT_ID'        => $PATIENT_PAYMENT_ID,
            'SERVICE_CHARGES_ITEM_ID'   => $SERVICE_CHARGES_ITEM_ID,
            'DISCOUNT'                  => $DISCOUNT > 0 ? $DISCOUNT : null,
            'AMOUNT_APPLIED'            => $AMOUNT_APPLIED,
            'DISCOUNT_ACCOUNT_ID'       => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_RECEIVABLE_ID'    => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null
        ]);

        return $ID;
    }

    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        PatientPayments::where('ID', $ID)->update([
            'FILE_NAME' => $FILE_NAME,
            'FILE_PATH' => $FILE_PATH
        ]);
    }
    public function PaymentChargesExist(int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID): int
    {
        $data = PatientPaymentCharges::where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->first();
        if ($data) {
            return $data->ID;
        }
        return 0;
    }

    public function PaymentChargesUpdate(int $ID, int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID, float $DISCOUNT, float $AMOUNT_APPLIED)
    {
        PatientPaymentCharges::where('ID', $ID)
            ->where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->update([
                'DISCOUNT' => $DISCOUNT,
                'AMOUNT_APPLIED' => $AMOUNT_APPLIED
            ]);
    }
    public function PaymentChargesDelete(int $ID, int $PATIENT_PAYMENT_ID, int $SERVICE_CHARGES_ITEM_ID)
    {

        PatientPaymentCharges::where('ID', $ID)
            ->where('PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->delete();
    }
    public function PaymentChargesList(int $PATIENT_PAYMENT_ID)
    {
        $result = PatientPaymentCharges::query()
            ->select([
                'patient_payment_charges.ID',
                'patient_payment_charges.SERVICE_CHARGES_ITEM_ID',
                'sc.ID as SERVICE_CHARGES_ID',
                'sc.DATE',
                'sc.CODE',
                'sc.AMOUNT',
                'sc.BALANCE_DUE',
                'i.DESCRIPTION as ITEM_NAME',
                'sci.QUANTITY',
                'unit_of_measure.SYMBOL',
                'sci.AMOUNT as ITEM_AMOUNT',
                'patient_payment_charges.AMOUNT_APPLIED',

            ])
            ->leftJoin('service_charges_items as sci', 'sci.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'sci.UNIT_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->where('patient_payment_charges.PATIENT_PAYMENT_ID', $PATIENT_PAYMENT_ID)
            ->get();

        return $result;
    }

    public function UpdatePaymentChargesApplied(int $PATIENT_PAYMENT_ID): float
    {
        $pay = (float) PatientPaymentCharges::query()
            ->select(DB::raw('IFNULL(SUM(patient_payment_charges.AMOUNT_APPLIED), 0) as pay'))
            ->where('patient_payment_charges.PATIENT_PAYMENT_ID', '=', $PATIENT_PAYMENT_ID)
            ->first()
            ->pay;

        PatientPayments::where('ID', $PATIENT_PAYMENT_ID)->update(['AMOUNT_APPLIED' => $pay]);

        return $pay;
    }

    public function ServiceChargesPaymentList(int $SERVICE_CHARGES_ID, int $PATIENT_PAYMENT_ID)
    {
        $result = PatientPayments::query()
            ->select([
                'patient_payment_charges.ID',
                'patient_payment_charges.PATIENT_PAYMENT_ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'patient_payment.AMOUNT',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment_charges.AMOUNT_APPLIED',
                'patient_payment.FILE_PATH',
                'patient_payment.IS_CONFIRM',
                'item.DESCRIPTION as ITEM_NAME',
                'service_charges_items.QUANTITY',
                'service_charges_items.AMOUNT as ITEM_AMOUNT',
                'service_charges_items.ID as SERVICE_CHARGES_ITEM_ID'
            ])
            ->join('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->join('patient_payment_charges', 'patient_payment_charges.PATIENT_PAYMENT_ID', '=', 'patient_payment.ID')
            ->join('service_charges_items', 'service_charges_items.ID', '=', 'patient_payment_charges.SERVICE_CHARGES_ITEM_ID')
            ->leftJoin('item', 'item.ID', '=', 'service_charges_items.ITEM_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_PAYMENT_ID)
            ->where('service_charges_items.SERVICE_CHARGES_ID', $SERVICE_CHARGES_ID)
            ->get();

        return $result;
    }

    public function PaymentAvailableList(int $PATIENT_ID, int $LOCATION_ID)
    {
        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED'
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_ID)
            ->where('patient_payment.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->get();

        return $result;
    }
    public function PaymentAvailableList_SC(int $PATIENT_ID, int $LOCATION_ID, int $serviceCharges_Item_Id)
    {

        $result = PatientPayments::query()
            ->select([
                'patient_payment.ID',
                'patient_payment.CODE',
                'patient_payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'patient_payment.AMOUNT',
                'patient_payment.AMOUNT_APPLIED',
                DB::raw("(select count( *) from patient_payment_charges as d where d.PATIENT_PAYMENT_ID = patient_payment.ID and d.service_charges_item_id =  " . $serviceCharges_Item_Id . "  ) as IS_COUNT ")
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'patient_payment.PAYMENT_METHOD_ID')
            ->where('patient_payment.PATIENT_ID', $PATIENT_ID)
            ->where('patient_payment.LOCATION_ID', $LOCATION_ID)
            ->whereRaw('(patient_payment.AMOUNT - patient_payment.AMOUNT_APPLIED) > 0')
            ->orderBy('patient_payment.DATE')
            ->get();

        return $result;
    }
    public function GetPaymentRemaining(int $PATIENT_PAYMENT_ID): float
    {
        $result = PatientPayments::where('ID', $PATIENT_PAYMENT_ID)->first();

        return (float) $result->AMOUNT - (float) $result->AMOUNT_APPLIED;
    }
    public function GetPaymentRemainingItem(int $SERVICE_CHARGES_ITEM_ID): float
    {
        $data = PatientPaymentCharges::where('SERVICE_CHARGES_ITEM_ID', $SERVICE_CHARGES_ITEM_ID)
            ->select(DB::raw(' IFNULL( SUM(AMOUNT_APPLIED),0) as TOTAL'))
            ->first();

        return (float) $data->TOTAL;
    }

    public function HaveRemainingPaymentBalance(int $PATIENT_PAYMENT_ID, int $LOCATION_ID): bool
    {
        $total = (float) PatientPayments::query()
            ->select(DB::raw('IFNULL(SUM(AMOUNT-AMOUNT_APPLIED), 0) AS TOTAL'))
            ->where('PATIENT_ID', $PATIENT_PAYMENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'asc')
            ->first()
            ->TOTAL;

        if ($total > 0) {
            return true;
        }
        return false;
    }
    
}
