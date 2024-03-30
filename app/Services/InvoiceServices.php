<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\PaymentInvoices;
use App\Models\Tax;
use Carbon\Carbon;
use Livewire\WithPagination;

class InvoiceServices
{
    use WithPagination;
    private $object;
    private $compute;
    private $locationReference;
    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        LocationReferenceServices $locationReferenceServices,

    ) {
        $this->object = $objectService;
        $this->compute = $computeServices;
        $this->locationReference = $locationReferenceServices;

    }
    public function getBalance(int $INVOICE_ID): float
    {
        return (float) Invoice::where('ID', $INVOICE_ID)->first()->BALANCE_DUE;
    }
    public function getInvoiceList(int $CUSTOMER_ID, int $LOCATION_ID, int $PAYMENT_ID)
    {
        return Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.DATE',
                'invoice.CODE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE'
            ])
            ->whereNotExists(function ($query) use (&$PAYMENT_ID) {
                $query->select(\DB::raw(1))
                    ->from('payment_invoices as p')
                    ->whereRaw('p.INVOICE_ID = invoice.ID')
                    ->where('p.PAYMENT_ID', '=', $PAYMENT_ID);
            })
            ->where('invoice.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('invoice.LOCATION_ID', $LOCATION_ID)
            ->where('invoice.BALANCE_DUE', '>', 0)
            ->get();
    }
    public function get(int $ID): object
    {
        return Invoice::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        int $SALES_REP_ID,
        string $PO_NUMBER,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        $SHIP_DATE,
        int $PAYMENT_TERMS_ID,
        $DUE_DATE,
        $DISCOUNT_DATE,
        float $DISCOUNT_PCT,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID

    ): int {

        $ID = (int) $this->object->ObjectNextID('INVOICE');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('INVOICE');

        Invoice::create([
            'ID' => $ID,
            'RECORDED_ON' => Carbon::now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'CLASS_ID' => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID' => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'PO_NUMBER' => $PO_NUMBER ?? '',
            'SHIP_TO' => $SHIP_TO ? $SHIP_TO : null,
            'SHIP_VIA_ID' => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
            'SHIP_DATE' => $SHIP_DATE ?? null,
            'PAYMENT_TERMS_ID' => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
            'DUE_DATE' => $DUE_DATE ?? null,
            'DISCOUNT_DATE' => $DISCOUNT_DATE ?? null,
            'DISCOUNT_PCT' => $DISCOUNT_PCT ?? null,
            'NOTES' => $NOTES ?? null,
            'AMOUNT' => 0,
            'BALANCE_DUE' => 0,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
            'STATUS' => $STATUS,
            'STATUS_DATE' => Carbon::now()->format('Y-m-d'),
            'OUTPUT_TAX_ID' => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE' => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_VAT_METHOD' => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID' => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,

        ]);

        return $ID;
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        Invoice::where('ID', $ID)->update([
            'STATUS' => $STATUS,
            'STATUS_DATE' => Carbon::now()->format('Y-m-d')
        ]);
    }
    public function Update(
        int $ID,
        string $CODE,
        string $DATE,
        int $CUSTOMER_ID,
        int $LOCATION_ID,
        int $CLASS_ID,
        int $SALES_REP_ID,
        string $PO_NUMBER,
        string $SHIP_TO,
        int $SHIP_VIA_ID,
        $SHIP_DATE,
        int $PAYMENT_TERMS_ID,
        $DUE_DATE,
        $DISCOUNT_DATE,
        float $DISCOUNT_PCT,
        string $NOTES,
        int $ACCOUNTS_RECEIVABLE_ID,
        int $STATUS,
        int $OUTPUT_TAX_ID,
        float $OUTPUT_TAX_RATE,
        int $OUTPUT_TAX_VAT_METHOD,
        int $OUTPUT_TAX_ACCOUNT_ID
    ): void {

        Invoice::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'CLASS_ID' => $CLASS_ID > 0 ? $CLASS_ID : null,
            'SALES_REP_ID' => $SALES_REP_ID > 0 ? $SALES_REP_ID : null,
            'PO_NUMBER' => $PO_NUMBER ?? '',
            'SHIP_TO' => $SHIP_TO ? $SHIP_TO : null,
            'SHIP_VIA_ID' => $SHIP_VIA_ID ? $SHIP_VIA_ID : null,
            'SHIP_DATE' => $SHIP_DATE ?? null,
            'PAYMENT_TERMS_ID' => $PAYMENT_TERMS_ID ? $PAYMENT_TERMS_ID : null,
            'DUE_DATE' => $DUE_DATE ?? null,
            'DISCOUNT_DATE' => $DISCOUNT_DATE ?? null,
            'DISCOUNT_PCT' => $DISCOUNT_PCT ?? null,
            'NOTES' => $NOTES ?? null,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID,
            'OUTPUT_TAX_ID' => $OUTPUT_TAX_ID ? $OUTPUT_TAX_ID : null,
            'OUTPUT_TAX_RATE' => $OUTPUT_TAX_RATE,
            'OUTPUT_TAX_VAT_METHOD' => $OUTPUT_TAX_VAT_METHOD,
            'OUTPUT_TAX_ACCOUNT_ID' => $OUTPUT_TAX_ACCOUNT_ID > 0 ? $OUTPUT_TAX_ACCOUNT_ID : null,
        ]);
    }

    public function Delete(int $ID): void
    {
        InvoiceItems::where('INVOICE_ID', $ID)->delete();
        Invoice::where('ID', $ID)->delete();
    }

    public function Search($search, int $locationId, int $perPage)
    {
        return Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invoice.DATE',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'invoice.OUTPUT_TAX_RATE',
                'invoice.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                't.NAME as TAX_NAME',
                's.DESCRIPTION as STATUS'
            ])
            ->join('contact as c', 'c.ID', '=', 'invoice.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'invoice.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'invoice.STATUS')
            ->leftJoin('tax as t', 't.ID', '=', 'invoice.OUTPUT_TAX_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('invoice.CODE', 'like', '%' . $search . '%')
                    ->orWhere('invoice.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('invoice.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('invoice.ID', 'desc')
            ->paginate($perPage);


    }

    private function getLine($Id): int
    {
        return (int) InvoiceItems::where('INVOICE_ID', $Id)->max('LINE_NO');
    }
    public function ItemStore(
        int $INVOICE_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $COGS_ACCOUNT_ID,
        int $ASSET_ACCOUNT_ID,
        int $INCOME_ACCOUNT_ID,
        int $REF_LINE_ID,
        int $BATCH_ID,
        int $GROUP_LINE_ID,
        bool $PRINT_IN_FORMS,
        bool $DEPOSITED,
        int $PRICE_LEVEL_ID,

    ): int {

        $LINE_NO = $this->getLine($INVOICE_ID) + 1;
        $ID = $this->object->ObjectNextID('INVOICE_ITEMS');

        InvoiceItems::create([
            'ID' => $ID,
            'INVOICE_ID' => $INVOICE_ID,
            'LINE_NO' => $LINE_NO,
            'ITEM_ID' => $ITEM_ID,
            'DESCRIPTION' => null,
            'QUANTITY' => $QUANTITY,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE' => $RATE,
            'RATE_TYPE' => $RATE_TYPE,
            'AMOUNT' => $AMOUNT,
            'TAXABLE' => $TAXABLE,
            'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
            'TAX_AMOUNT' => $TAX_AMOUNT,
            'COGS_ACCOUNT_ID' => $COGS_ACCOUNT_ID > 0 ? $COGS_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'INCOME_ACCOUNT_ID' => $INCOME_ACCOUNT_ID > 0 ? $INCOME_ACCOUNT_ID : null,
            'REF_LINE_ID' => $REF_LINE_ID > 0 ? $REF_LINE_ID : null,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
            'GROUP_LINE_ID' => $GROUP_LINE_ID > 0,
            'PRINT_IN_FORMS' => $PRINT_IN_FORMS,
            'DEPOSITED' => $DEPOSITED,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null

        ]);
        return $ID;
    }
    public function ItemUpdate(
        int $ID,
        int $INVOICE_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $RATE,
        int $RATE_TYPE,
        float $AMOUNT,
        bool $TAXABLE,
        float $TAXABLE_AMOUNT,
        float $TAX_AMOUNT,
        int $BATCH_ID,
        int $PRICE_LEVEL_ID,
    ) {
        InvoiceItems::where('ID', $ID)->where('INVOICE_ID', $INVOICE_ID)->where('ITEM_ID', $ITEM_ID)->update([
            'QUANTITY' => $QUANTITY,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'RATE' => $RATE,
            'AMOUNT' => $AMOUNT,
            'TAXABLE' => $TAXABLE,
            'TAXABLE_AMOUNT' => $TAXABLE_AMOUNT,
            'TAX_AMOUNT' => $TAX_AMOUNT,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null
        ]);
    }
    public function ItemDelete(int $ID, int $INVOICE_ID)
    {
        InvoiceItems::where('ID', $ID)->where('INVOICE_ID', $INVOICE_ID)->delete();
    }
    public function ItemView(int $INVOICE_ID)
    {
        return InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.ITEM_ID',
                'invoice_items.INVOICE_ID',
                'invoice_items.QUANTITY',
                'invoice_items.UNIT_ID',
                'invoice_items.RATE',
                'invoice_items.AMOUNT',
                'invoice_items.TAXABLE',
                'invoice_items.TAXABLE_AMOUNT',
                'i.CODE',
                'i.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                'c.DESCRIPTION as CLASS_DESCRIPTION'
            ])
            ->leftJoin('item as i', 'i.ID', '=', 'invoice_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'invoice_items.UNIT_ID')
            ->leftJoin('item_sub_class as sl', 'sl.ID', '=', 'i.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sl.CLASS_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();
    }
    public function ReComputed(int $ID): array
    {
        $invoiceItems = Invoice::where('ID', $ID)->first();
        if ($invoiceItems) {
            $TAX_ID = (int) $invoiceItems->OUTPUT_TAX_ID;

            $itemResult = InvoiceItems::query()
                ->select(
                    [
                        'invoice_items.AMOUNT',
                        'invoice_items.TAX_AMOUNT',
                        'invoice_items.TAXABLE_AMOUNT',
                        'invoice_items.TAXABLE',
                        'item.TYPE'
                    ]
                )
                ->join('item', 'item.ID', '=', 'invoice_items.ITEM_ID')
                ->where('invoice_items.INVOICE_ID', $ID)
                ->whereIn('item.TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->orderBy('invoice_items.LINE_NO', 'asc')
                ->get();

            $data = $this->compute->taxCompute($itemResult, $TAX_ID);

            foreach ($data as $list) {
                $originalAmount = (float) $list['AMOUNT'];
                $balance = (float) $originalAmount - $this->GetPaymentAppliedViaInvoice($ID);
                Invoice::where('ID', $ID)->update([
                    'AMOUNT' => $originalAmount,
                    'BALANCE_DUE' => $balance,
                    'OUTPUT_TAX_AMOUNT' => $list['TAX_AMOUNT'],
                    'TAXABLE_AMOUNT' => $list['TAXABLE_AMOUNT'],
                    'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT']
                ]);

                $result = array(
                    [
                        'AMOUNT' => $originalAmount,
                        'BALANCE_DUE' => $balance,
                        'TAX_AMOUNT' => $list['TAX_AMOUNT'],
                        'TAXABLE_AMOUNT' => $list['TAXABLE_AMOUNT'],
                        'NONTAXABLE_AMOUNT' => $list['NONTAXABLE_AMOUNT']
                    ]
                );

                return $result;
            }
        }
        return [];
    }

    public function GetPaymentAppliedViaInvoice(int $INVOICE_ID): float
    {
        $paymentSum = PaymentInvoices::query()
            ->select(\DB::raw('IFNULL(SUM(payment_invoices.AMOUNT_APPLIED), 0) AS pay'))
            ->where('payment_invoices.INVOICE_ID', '=', $INVOICE_ID)
            ->whereNull('payment_invoices.ACCOUNTS_RECEIVABLE_ID')
            ->first();

        return $paymentSum->pay;

    }

    public function updateInvoiceBalance(int $INVOICE_ID)
    {
        $data = Invoice::where('ID', $INVOICE_ID)->first();

        if ($data) {
            $AMOUNT = (float) $data->AMOUNT;
            $PAYMENT = (float) $this->GetPaymentAppliedViaInvoice($INVOICE_ID);
            $BALANCE = $AMOUNT - $PAYMENT;
        
            $STATUS = 0;

            if ($PAYMENT == 0) {
                // poste    d
                $STATUS = 0;
            } elseif ($BALANCE <= 0) {
                //paid
                $STATUS = 11;
            } else {
                // Unpaid
                $STATUS = 2;
            }

            Invoice::where('ID', $INVOICE_ID)->update([
                'BALANCE_DUE' => $BALANCE,
                'STATUS' => $STATUS,
                'STATUS_DATE' => Carbon::now()->format('Y-m-d')
            ]);


        }
    }


    public function getUpdateTaxItem(int $INVOICE_ID, int $TAX_ID)
    {
        $items = InvoiceItems::query()
            ->select([
                'invoice_items.ID',
                'invoice_items.AMOUNT',
                'invoice_items.TAXABLE'
            ])
            ->join('item', 'item.ID', '=', 'invoice_items.ITEM_ID')
            ->where('invoice_items.INVOICE_ID', $INVOICE_ID)
            ->where('item.TYPE', 0)
            ->orderBy('invoice_items.LINE_NO', 'asc')
            ->get();

        $taxRate = (float) Tax::where('ID', $TAX_ID)->first()->RATE;
        foreach ($items as $list) {
            $tax_result = $this->compute->ItemComputeTax($list->AMOUNT, $list->TAXABLE, $TAX_ID, $taxRate);
            if ($tax_result) {
                InvoiceItems::where('ID', $list->ID)
                    ->update([
                        'TAXABLE_AMOUNT' => $tax_result['TAXABLE_AMOUNT'],
                        'TAX_AMOUNT' => $tax_result['TAX_AMOUNT']
                    ]);
            }
        }
    }
}