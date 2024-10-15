<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\BillCreditBills;
use App\Models\BillExpenses;
use App\Models\BillItems;
use App\Models\CheckBills;
use App\Models\Tax;
use Illuminate\Support\Facades\DB;

class BillingServices
{

    public int $object_type_map_bill = 2;
    public int $object_type_map_bill_item = 3;
    public int $object_type_map_bill_expenses = 78;
    public int $document_type_id = 1;

    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices

    ) {
        $this->object = $objectService;
        $this->compute = $computeServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function ConfirmProccess(int $ID)
    {
        Bill::where('ID', '=', $ID)
            ->update(['DATE_CONFIRM' => $this->dateServices->NowDate()]);
    }
    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Bill::where('ID', $ID)
        ->update([
            'FILE_NAME' => $FILE_NAME,
            'FILE_PATH' => $FILE_PATH
        ]);
    }
    public function get(int $ID)
    {
        try {

            $data = Bill::where('ID', $ID)->first();
            if ($data) {
                return $data;
            }
            return [];
        } catch (\Throwable $th) {
            return [];
        }
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $VENDOR_ID,
        int $LOCATION_ID,
        int $PAYMENT_TERMS_ID,
        string $DUE_DATE,
        string $DISCOUNT_DATE,
        float $DISCOUNT_PCT,
        string $NOTES,
        int $ACCOUNTS_PAYABLE_ID,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        float $INPUT_TAX_AMOUNT,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID,
        int $STATUS
    ): int {
        $ID = (int) $this->object->ObjectNextID('BILL');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BILL');
        $isLocRef = (bool) boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Bill::create([
            'ID'                => $ID,
            'RECORDED_ON'       => $this->dateServices->Now(),
            'DATE'              => $DATE,
            'CODE'              => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'VENDOR_ID'         => $VENDOR_ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'PAYMENT_TERMS_ID'  => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : 0,
            'DUE_DATE'          => $DUE_DATE ? $DUE_DATE : null,
            'DISCOUNT_DATE'     => $DISCOUNT_DATE ? $DISCOUNT_DATE : null,
            'DISCOUNT_PCT'      => $DISCOUNT_PCT > 0 ? $DISCOUNT_PCT : 0,
            'AMOUNT'            => 0,
            'BALANCE_DUE'       => 0,
            'NOTES'             => $NOTES,
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID > 0 ? $ACCOUNTS_PAYABLE_ID : null,
            'INPUT_TAX_ID'      => $INPUT_TAX_ID,
            'INPUT_TAX_RATE'    => $INPUT_TAX_RATE,
            'INPUT_TAX_AMOUNT'  => $INPUT_TAX_AMOUNT,
            'INPUT_TAX_VAT_METHOD' => $INPUT_TAX_VAT_METHOD,
            'INPUT_TAX_ACCOUNT_ID' => $INPUT_TAX_ACCOUNT_ID,
            'STATUS'            => $STATUS,
            'STATUS_DATE'       => $this->dateServices->NowDate()
        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        int $VENDOR_ID,
        int $PAYMENT_TERMS_ID,
        string $DUE_DATE,
        string $NOTES,
        int $ACCOUNTS_PAYABLE_ID,
        int $INPUT_TAX_ID,
        float $INPUT_TAX_RATE,
        float $INPUT_TAX_AMOUNT,
        int $INPUT_TAX_VAT_METHOD,
        int $INPUT_TAX_ACCOUNT_ID
    ) {


        Bill::where('ID', $ID)
            ->update([
                'CODE'                      => $CODE,
                'VENDOR_ID'                 => $VENDOR_ID,
                'PAYMENT_TERMS_ID'          => $PAYMENT_TERMS_ID > 0 ? $PAYMENT_TERMS_ID : null,
                'DUE_DATE'                  => $DUE_DATE ? $DUE_DATE : null,
                'NOTES'                     => $NOTES,
                'ACCOUNTS_PAYABLE_ID'       => $ACCOUNTS_PAYABLE_ID,
                'INPUT_TAX_ID'              => $INPUT_TAX_ID,
                'INPUT_TAX_RATE'            => $INPUT_TAX_RATE,
                'INPUT_TAX_AMOUNT'          => $INPUT_TAX_AMOUNT,
                'INPUT_TAX_VAT_METHOD'      => $INPUT_TAX_VAT_METHOD,
                'INPUT_TAX_ACCOUNT_ID'      => $INPUT_TAX_ACCOUNT_ID,
            ]);
    }

    public function Delete(int $ID)
    {
        $data = $this->get($ID);
        if ($data->STATUS ==  0) {
            BillItems::where('BILL_ID', $ID)->delete();
            BillExpenses::where('BILL_ID', $ID)->delete();
            Bill::where('ID', $ID)->delete();
            return true;
        }
        return false;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Bill::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.CODE',
                'bill.DATE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE',
                'bill.INPUT_TAX_RATE',
                'bill.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS',
                'bill.STATUS  as STATUS_ID'
            ])
            ->join('contact as c', 'c.ID', '=', 'bill.VENDOR_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'bill.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'bill.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'bill.INPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('bill.CODE', 'like', '%' . $search . '%')
                        ->orWhere('bill.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('bill.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $BILL_ID, bool $isItem): int
    {
        if ($isItem == true) {
            return (int) BillItems::where('BILL_ID', $BILL_ID)->count();
        }
        return (int) BillExpenses::where('BILL_ID', $BILL_ID)->count();
    }
    private function getLine(int $Id, bool $isItem): int
    {
        if ($isItem) {
            return (int) BillItems::where('BILL_ID', $Id)->max('LINE_NO');
        }
        return (int) BillExpenses::where('BILL_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(
        int $BILL_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        int $BATCH_ID,
        int $ACCOUNT_ID,
        int $PO_ITEM_ID = 9,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $CLASS_ID = 0
    ): int {

        $LINE_NO = $this->getLine($BILL_ID, true) + 1;
        $ID = $this->object->ObjectNextID('BILL_ITEMS');

        BillItems::create([
            'ID'                    => $ID,
            'BILL_ID'               => $BILL_ID,
            'LINE_NO'               => $LINE_NO,
            'ITEM_ID'               => $ITEM_ID,
            'DESCRIPTION'           => null,
            'QUANTITY'              => $QUANTITY,
            'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY'    => $UNIT_BASE_QUANTITY,
            'RATE'                  => $RATE,
            'RATE_TYPE'             => $RATE_TYPE,
            'AMOUNT'                => $AMOUNT,
            'BATCH_ID'              => $BATCH_ID > 0 ? $BATCH_ID : null,
            'ACCOUNT_ID'            => $ACCOUNT_ID,
            'PO_ITEM_ID'            => $PO_ITEM_ID > 0 ? $PO_ITEM_ID : null,
            'TAXABLE'               => $TAXABLE,
            'TAXABLE_AMOUNT'        => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'            => $TAX_AMOUNT,
            'CLASS_ID'              => $CLASS_ID > 0 ? $CLASS_ID : null,
        ]);

        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $BILL_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT
    ) {

        BillItems::where('ID', $ID)->where('BILL_ID', $BILL_ID)->where('ITEM_ID', $ITEM_ID)
            ->update([
                'QUANTITY'              => $QUANTITY,
                'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY'    => $UNIT_BASE_QUANTITY,
                'RATE'                  => $RATE,
                'AMOUNT'                => $AMOUNT,
                'TAXABLE'               => $TAXABLE,
                'TAXABLE_AMOUNT'        => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'            => $TAX_AMOUNT
            ]);
    }

    public function ItemGet(int $ID, int $BILL_ID)
    {
        $result =  BillItems::where('ID', '=', $ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->first();

        return $result;
    }
    public function ItemDelete(int $ID, int $BILL_ID)
    {
        BillItems::where('ID', $ID)
            ->where('BILL_ID', $BILL_ID)
            ->delete();
    }
    public function ItemView(int $BILL_ID)
    {
        return BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.BILL_ID',
                'bill_items.QUANTITY',
                'bill_items.UNIT_ID',
                'bill_items.RATE',
                'bill_items.AMOUNT',
                'bill_items.TAXABLE',
                'bill_items.TAXABLE_AMOUNT',
                'bill_items.ACCOUNT_ID',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL'
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'bill_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'bill_items.UNIT_ID')
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->orderBy('bill_items.LINE_NO', 'asc')
            ->get();
    }
    public function ExpenseStore(
        int $BILL_ID,
        int $ACCOUNT_ID,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        string $PARTICULARS,
        int $CLASS_ID = 0
    ): int {
        $LINE_NO = $this->getLine($BILL_ID, false) + 1;
        $ID = (int)  $this->object->ObjectNextID('BILL_EXPENSES');

        BillExpenses::create([
            'ID'                => $ID,
            'BILL_ID'           => $BILL_ID,
            'LINE_NO'           => $LINE_NO,
            'ACCOUNT_ID'        => $ACCOUNT_ID,
            'AMOUNT'            => $AMOUNT,
            'TAXABLE'           => $TAXABLE,
            'TAXABLE_AMOUNT'    => $TAXABLE_AMOUNT,
            'TAX_AMOUNT'        => $TAX_AMOUNT,
            'PARTICULARS'       => $PARTICULARS,
            'CLASS_ID'          => $CLASS_ID > 0 ? $CLASS_ID : null

        ]);

        return $ID;
    }
    public function ExpenseUpdate(int $ID, int $BILL_ID, float $AMOUNT, bool $TAXABLE, float $TAXABLE_AMOUNT, float $TAX_AMOUNT, string $PARTICULARS, int $CLASS_ID = 0)
    {
        BillExpenses::where('ID', $ID)
            ->where('BILL_ID', $BILL_ID)
            ->update([
                'AMOUNT'            => $AMOUNT,
                'TAXABLE'           => $TAXABLE,
                'TAXABLE_AMOUNT'    => $TAXABLE_AMOUNT,
                'TAX_AMOUNT'        => $TAX_AMOUNT,
                'PARTICULARS'       => $PARTICULARS,
                'CLASS_ID'          => $CLASS_ID > 0 ? $CLASS_ID : null
            ]);
    }
    public function ExpenseDelete(int $ID, int $BILL_ID,)
    {
        BillExpenses::where('ID', $ID)->where('BILL_ID', $BILL_ID)->delete();
    }
    public function ExpenseGet(int $ID, $BILL_ID)
    {
        $data =  BillExpenses::where('ID', '=', $ID)
            ->where('BILL_ID', '=', $BILL_ID)
            ->first();

        if ($data) {
            return $data;
        }

        return [];
    }
    public function ExpenseView(int $BILL_ID)
    {
        $result = BillExpenses::query()
            ->select([
                'bill_expenses.ID',
                'bill_expenses.ACCOUNT_ID',
                'bill_expenses.AMOUNT',
                'bill_expenses.PARTICULARS',
                'bill_expenses.TAXABLE',
                'bill_expenses.CLASS_ID',
                'bill_expenses.ACCOUNT_ID',
                'a.NAME',
                'a.TAG as CODE'
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'bill_expenses.ACCOUNT_ID')
            ->where('bill_expenses.BILL_ID', $BILL_ID)
            ->orderBy('bill_expenses.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function isItemTab($BILL_ID): bool
    {
        $ItemCount = $this->getLine($BILL_ID, true);
        $AccountCount = $this->getLine($BILL_ID, false);
        if ($ItemCount >= $AccountCount) {
            return true;
        }
        return false;
    }
    public function ReComputed(int $ID): array
    {
        $BILL = Bill::where('ID', $ID)->first();
        if ($BILL) {
            $TAX_ID = (int) $BILL->INPUT_TAX_ID;
            $itemResult = BillItems::query()
                ->select(
                    [
                        'bill_items.AMOUNT',
                        'bill_items.TAX_AMOUNT',
                        'bill_items.TAXABLE_AMOUNT',
                        'bill_items.TAXABLE'
                    ]
                )
                ->where('bill_items.BILL_ID', $ID)
                ->orderBy('bill_items.LINE_NO', 'asc')
                ->get();
            $expensesResult = BillExpenses::query()
                ->select(
                    [
                        'bill_expenses.AMOUNT',
                        'bill_expenses.TAX_AMOUNT',
                        'bill_expenses.TAXABLE_AMOUNT',
                        'bill_expenses.TAXABLE'
                    ]
                )
                ->where('bill_expenses.BILL_ID', $ID)
                ->orderBy('bill_expenses.LINE_NO', 'asc')
                ->get();

            $result = $this->compute->taxComputeWithExpenses($itemResult, $expensesResult, $TAX_ID);
            foreach ($result as $list) {
                Bill::where('ID', $ID)->update([
                    'AMOUNT' => $list['AMOUNT'],
                    'BALANCE_DUE' => $list['AMOUNT'],
                    'INPUT_TAX_AMOUNT' => $list['TAX_AMOUNT']
                ]);
            }
            return $result;
        }

        return [];
    }
    public function getUpdateTaxItem(int $BILL_ID, int $TAX_ID)
    {
        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE ?? 0;
        $items = BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.AMOUNT',
                'bill_items.TAXABLE'
            ])
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->orderBy('bill_items.LINE_NO', 'asc')
            ->get();

        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                BillItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT'    => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT'        => $tax_result['TAX_AMOUNT']
                    ]);
            }
        }

        $expenses = BillExpenses::query()
            ->select([
                'bill_expenses.ID',
                'bill_expenses.AMOUNT',
                'bill_expenses.TAXABLE'
            ])
            ->where('bill_expenses.BILL_ID', $BILL_ID)
            ->orderBy('bill_expenses.LINE_NO', 'asc')
            ->get();

        foreach ($expenses as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);

            BillExpenses::where('ID', $list->ID)
                ->update([
                    'TAXABLE_AMOUNT'        => $tax_result['TAXABLE_AMOUNT'],
                    'TAX_AMOUNT'            => $tax_result['TAX_AMOUNT']
                ]);
        }
    }
    public function GetBillPaymentApplied(int $BILL_ID): float
    {
        $result = CheckBills::query()
            ->select(DB::raw('IFNULL(SUM(check_bills.AMOUNT_PAID), 0) AS pay'))
            ->where('check_bills.BILL_ID', '=', $BILL_ID)
            ->first();

        return (float) $result->pay ?? 0;
    }

    public function GetBillCreditApplied(int $BILL_ID): float
    {
        $result = BillCreditBills::query()
            ->select(DB::raw('IFNULL(SUM(bill_credit_bills.AMOUNT_APPLIED), 0) AS pay'))
            ->where('bill_credit_bills.BILL_ID', '=', $BILL_ID)
            ->first();

        return (float) $result->pay ?? 0;
    }

    public function UpdateBalance(int $BILL_ID)
    {
        $PAYMENT = $this->GetBillPaymentApplied($BILL_ID);
        $CREDIT = $this->GetBillCreditApplied($BILL_ID);
        $PAY = (float) $PAYMENT + $CREDIT;

        $data = Bill::where('ID', $BILL_ID)->first();
        if ($data) {
            $AMOUNT = (float) $data->AMOUNT;
            $BALANCE = $AMOUNT - $PAY;
            $STATUS = 0;

            if ($PAY == 0) {
                // poste 
                $STATUS = 0;
            } elseif ($BALANCE <= 0) {
                //paid
                $STATUS = 11;
            } else {
                // Unpaid
                $STATUS = 2;
            }

            Bill::where('ID', $BILL_ID)
                ->update([
                    'BALANCE_DUE'   => $BALANCE,
                    'STATUS'        => $STATUS,
                    'STATUS_DATE'   => $this->dateServices->NowDate()
                ]);
        }
    }
    public function getBalance(int $BILL_ID): float
    {
        $data = Bill::where('ID', $BILL_ID)->first();
        if ($data) {
            return (float) $data->BALANCE_DUE;
        }
        return 0;
    }
    public function getBillListViaBillPayment(int $VENDOR_ID, int $LOCATION_ID, int $CHECK_ID)
    {
        $result = Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE'
            ])
            ->whereNotExists(function ($query) use (&$CHECK_ID) {
                $query->select(DB::raw(1))
                    ->from('check_bills as b')
                    ->whereRaw('b.BILL_ID = bill.ID')
                    ->where('b.CHECK_ID', '=', $CHECK_ID);
            })
            ->where('bill.VENDOR_ID', $VENDOR_ID)
            ->where('bill.LOCATION_ID', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->get();

        return $result;
    }
    public function getBillListViaBillCredit(int $VENDOR_ID, int $LOCATION_ID, int $BILL_CREDIT_ID)
    {
        return Bill::query()
            ->select([
                'bill.ID',
                'bill.DATE',
                'bill.CODE',
                'bill.AMOUNT',
                'bill.BALANCE_DUE'
            ])
            ->whereNotExists(function ($query) use (&$BILL_CREDIT_ID) {
                $query->select(DB::raw(1))
                    ->from('bill_credit_bills as p')
                    ->whereRaw('p.BILL_ID = bill.ID')
                    ->where('p.BILL_CREDIT_ID', '=', $BILL_CREDIT_ID);
            })
            ->where('bill.VENDOR_ID', $VENDOR_ID)
            ->where('bill.LOCATION_ID', $LOCATION_ID)
            ->where('bill.BALANCE_DUE', '>', 0)
            ->get();
    }
    public function PaymentHistory($BILL_ID)
    {
        $results = DB::table(DB::raw('
        (
            SELECT \'Pay Bills\' AS `TYPE`, check_bills.`ID`, check_bills.`CHECK_ID` AS MAIN_ID, check_bills.`BILL_ID`, check_bills.AMOUNT_PAID as `AMOUNT_APPLIED`, `check`.`RECORDED_ON`,`check`.CODE,`check`.DATE
            FROM check_bills  
            INNER JOIN `check` ON `check`.`ID` = check_bills.`CHECK_ID`
            UNION 
            SELECT \'Bill Credits\' AS `TYPE`, bill_credit_bills.`ID`, bill_credit_bills.`BILL_CREDIT_ID` AS MAIN_ID, bill_credit_bills.`BILL_ID`, bill_credit_bills.`AMOUNT_APPLIED`, bill_credit.`RECORDED_ON`,bill_credit.CODE,bill_credit.DATE
            FROM bill_credit_bills 
            INNER JOIN bill_credit ON bill_credit.`ID` = bill_credit_bills.`BILL_CREDIT_ID`
        ) AS pay'))
            ->select('pay.TYPE', 'pay.ID', 'pay.MAIN_ID', 'pay.BILL_ID', 'pay.AMOUNT_APPLIED', 'pay.RECORDED_ON', 'pay.CODE', 'pay.DATE')
            ->where('pay.BILL_ID', '=', $BILL_ID)
            ->orderBy('pay.RECORDED_ON')
            ->get();

        return $results;
    }

    public function ItemInventory(int $BILL_ID)
    {
        $result = BillItems::query()
            ->select([
                'bill_items.ID',
                'bill_items.ITEM_ID',
                'bill_items.RATE',
                'bill_items.QUANTITY',
                'bill_items.UNIT_BASE_QUANTITY',
                'bill_items.RATE',
                'item.COST'
            ])
            ->join('item', 'item.ID', '=', 'bill_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('bill_items.BILL_ID', $BILL_ID)
            ->get();

        return $result;
    }
    public function getBillTaxJournal(int $BILL_ID)
    {
        $result = Bill::query()
            ->select([
                'ID',
                'INPUT_TAX_ACCOUNT_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'INPUT_TAX_AMOUNT as AMOUNT',
                DB::raw(' 0 as ENTRY_TYPE')

            ])
            ->where('ID', $BILL_ID)
            ->get();

        return $result;
    }
    public function getBillJournal(int $BILL_ID)
    {
        $result = Bill::query()
            ->select([
                'ID',
                'ACCOUNTS_PAYABLE_ID as ACCOUNT_ID',
                'VENDOR_ID as SUBSIDIARY_ID',
                'AMOUNT',
                DB::raw(' 1 as ENTRY_TYPE')
            ])
            ->where('ID', $BILL_ID)->get();

        return $result;
    }
    public function getBillItemJournal(int $BILL_ID)
    {
        $result = BillItems::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ITEM_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1)  as ENTRY_TYPE')
            ])
            ->where('BILL_ID', $BILL_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getBillExpenseJournal(int $BILL_ID)
    {
        $result = BillExpenses::query()
            ->select([
                'ID',
                'ACCOUNT_ID',
                'ACCOUNT_ID as SUBSIDIARY_ID',
                DB::raw('IF(TAXABLE_AMOUNT > 0, TAXABLE_AMOUNT, AMOUNT) as AMOUNT'),
                DB::raw('IF(AMOUNT >= 0, 0, 1) as ENTRY_TYPE')
            ])
            ->where('BILL_ID', $BILL_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }



    public function uploadFile() {}

    public function fileConfirm() {}
}
