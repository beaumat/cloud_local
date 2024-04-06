<?php
namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentInvoices;
use Carbon\Carbon;
use Livewire\WithPagination;

class PaymentServices
{

    use WithPagination;
    public int $PAYMENT_INVOICE_ID;
    public int $INVOICE_ID;
    public float $DISCOUNT;
    public float $AMOUNT_APPLIED;
    public int $DISCOUNT_ACCOUNT_ID;
    public int $ACCOUNTS_RECEIVABLE_ID;
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
        // $this->invoiceServices = $invoiceServices;
    }
    public function get($ID)
    {
        return Payment::where('ID', $ID)->first();
    }

    public function Store(
        string $CODE,
        $DATE,
        int $CUSTOMER_ID,
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

        $ID = (int) $this->object->ObjectNextID('PAYMENT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PAYMENT');
        Payment::create([
            'ID' => $ID,
            'RECORDED_ON' => Carbon::now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
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
            'STATUS_DATE' => Carbon::now()->format('Y-m-d'),
            'DEPOSITED' => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID
        ]);
        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        $DATE,
        int $CUSTOMER_ID,
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
    ) {
        Payment::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'AMOUNT' => $AMOUNT,
            'PAYMENT_METHOD_ID' => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'CARD_NO' => $CARD_NO,
            'CARD_EXPIRY_DATE' => $CARD_EXPIRY_DATE ?? null,
            'RECEIPT_REF_NO' => $RECEIPT_REF_NO,
            'RECEIPT_DATE' => $RECEIPT_DATE ?? null,
            'NOTES' => $NOTES,
            'UNDEPOSITED_FUNDS_ACCOUNT_ID' => $UNDEPOSITED_FUNDS_ACCOUNT_ID > 0 ? $UNDEPOSITED_FUNDS_ACCOUNT_ID : null,
            'OVERPAYMENT_ACCOUNT_ID' => $OVERPAYMENT_ACCOUNT_ID > 0 ? $OVERPAYMENT_ACCOUNT_ID : null,
            'DEPOSITED' => $DEPOSITED,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID
        ]);


    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Payment::where('ID', $ID)->update([
            'STATUS' => $STATUS,
            'STATUS_DATE' => Carbon::now()->format('Y-m-d')
        ]);
    }
    public function Delete(int $ID)
    {
        PaymentInvoices::where('PAYMENT_ID', $ID)->delete();
        Payment::where('ID', $ID)->delete();
    }
    public function Search($search, int $locationId, int $perPage)
    {
        return Payment::query()
            ->select([
                'payment.ID',
                'payment.CODE',
                'payment.DATE',
                'payment.AMOUNT',
                'payment.AMOUNT_APPLIED',
                'payment.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'pm.DESCRIPTION as PAYMENT_METHOD',
                'payment.FILE_PATH'

            ])
            ->join('contact as c', 'c.ID', '=', 'payment.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'payment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'payment.STATUS')
            ->join('payment_method as pm', 'pm.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('payment.CODE', 'like', '%' . $search . '%')
                    ->orWhere('payment.AMOUNT_APPLIED', 'like', '%' . $search . '%')
                    ->orWhere('payment.NOTES', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('payment.ID', 'desc')
            ->paginate($perPage);
    }


    public function PaymentInvoiceStore(
        int $PAYMENT_ID,
        int $INVOICE_ID,
        float $DISCOUNT,
        float $AMOUNT_APPLIED,
        int $DISCOUNT_ACCOUNT_ID,
        int $ACCOUNTS_RECEIVABLE_ID
    ): int {
        $ID = $this->object->ObjectNextID('PAYMENT_INVOICES');

        PaymentInvoices::create([
            'ID' => $ID,
            'PAYMENT_ID' => $PAYMENT_ID,
            'INVOICE_ID' => $INVOICE_ID,
            'DISCOUNT' => $DISCOUNT > 0 ? $DISCOUNT : null,
            'AMOUNT_APPLIED' => $AMOUNT_APPLIED,
            'DISCOUNT_ACCOUNT_ID' => $DISCOUNT_ACCOUNT_ID > 0 ? $DISCOUNT_ACCOUNT_ID : null,
            'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID > 0 ? $ACCOUNTS_RECEIVABLE_ID : null
        ]);
        return $ID;
    }

    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Payment::where('ID', $ID)->update([
            'FILE_NAME' => $FILE_NAME,
            'FILE_PATH' => $FILE_PATH
        ]);
    }
    public function PaymentInvoiceExist(int $PAYMENT_ID, int $INVOICE_ID): int
    {
        $data = PaymentInvoices::where('PAYMENT_ID', $PAYMENT_ID)->where('INVOICE_ID', $INVOICE_ID)->first();
        if ($data) {
            return $data->ID;
        }
        return 0;
    }

    public function PaymentInvoiceUpdate(int $ID, int $PAYMENT_ID, int $INVOICE_ID, float $DISCOUNT, float $AMOUNT_APPLIED)
    {
        PaymentInvoices::where('ID', $ID)->where('PAYMENT_ID', $PAYMENT_ID)->where('INVOICE_ID', $INVOICE_ID)->update([
            'DISCOUNT' => $DISCOUNT,
            'AMOUNT_APPLIED' => $AMOUNT_APPLIED
        ]);
    }
    public function PaymentInvoiceDelete(int $ID, int $PAYMENT_ID, int $INVOICE_ID)
    {
        PaymentInvoices::where('ID', $ID)->where('PAYMENT_ID', $PAYMENT_ID)->where('INVOICE_ID', $INVOICE_ID)->delete();
    }
    public function PaymentInvoiceList(int $PAYMENT_ID)
    {
        return PaymentInvoices::query()
            ->select([
                'payment_invoices.ID',
                'payment_invoices.INVOICE_ID',
                'i.DATE',
                'i.CODE',
                'i.AMOUNT',
                'i.BALANCE_DUE',
                'payment_invoices.AMOUNT_APPLIED'
            ])
            ->leftJoin('invoice as i', 'i.ID', '=', 'payment_invoices.INVOICE_ID')
            ->where('payment_invoices.PAYMENT_ID', $PAYMENT_ID)
            ->get();
    }

    public function UpdatePaymentApplied(int $PAYMENT_ID): float
    {
        $pay = PaymentInvoices::query()
            ->select(\DB::raw('IFNULL(SUM(payment_invoices.AMOUNT_APPLIED), 0) as pay'))
            ->where('payment_invoices.PAYMENT_ID', '=', $PAYMENT_ID)
            ->first()
            ->pay;

        Payment::where('ID', $PAYMENT_ID)->update(['AMOUNT_APPLIED' => $pay]);
        return $pay;
    }
    public function InvoicePaymentList(int $INVOICE_ID, int $CUSTOMER_ID)
    {
        return Payment::query()
            ->select([
                'payment_invoices.ID',
                'payment_invoices.PAYMENT_ID',
                'payment.CODE',
                'payment.DATE',
                'payment.AMOUNT',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'payment_invoices.AMOUNT_APPLIED',
                'payment.FILE_PATH'
            ])
            ->join('payment_method', 'payment_method.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->join('payment_invoices', 'payment_invoices.PAYMENT_ID', '=', 'payment.ID')
            ->where('payment_invoices.INVOICE_ID', $INVOICE_ID)
            ->where('payment.CUSTOMER_ID', $CUSTOMER_ID)
            ->get();
    }
    public function PaymentAvailableList(int $CUSTOMER_ID, int $LOCATION_ID)
    {

        $result = Payment::query()
            ->select([
                'payment.ID',
                'payment.CODE',
                'payment.DATE',
                'payment_method.DESCRIPTION as PAYMENT_METHOD',
                'payment.AMOUNT',
                'payment.AMOUNT_APPLIED'
            ])
            ->leftJoin('payment_method', 'payment_method.ID', '=', 'payment.PAYMENT_METHOD_ID')
            ->where('payment.CUSTOMER_ID', $CUSTOMER_ID)
            ->where('payment.LOCATION_ID', $LOCATION_ID)
            ->where('payment.AMOUNT_APPLIED', '<>', 'payment.AMOUNT')
            ->get();

        return $result;
    }
    public function GetPaymentRemaining(int $PAYMENT_ID): float
    {
        $result = Payment::where('ID', $PAYMENT_ID)->first();

        return (float) $result->AMOUNT - (float) $result->AMOUNT_APPLIED;
    }


}