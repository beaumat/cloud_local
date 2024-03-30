<?php

namespace App\Livewire\ServiceCharge;

use App\Services\AccountServices;
use App\Services\InvoiceServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\SystemSettingServices;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PaymentModalNewEntry extends Component
{

    use WithFileUploads;
    public $PDF;

    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $INVOICE_ID;
    public int $ID;
    public string $CODE;
    public $DATE;

    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public int $PAYMENT_METHOD_ID;
    public string $CARD_NO;
    public $CARD_EXPIRY_DATE;
    public string $RECEIPT_REF_NO;
    public $RECEIPT_DATE;
    public string $NOTES;
    public int $UNDEPOSITED_FUNDS_ACCOUNT_ID;
    public int $OVERPAYMENT_ACCOUNT_ID;
    public int $STATUS;
    public string $STATUS_DATE;
    public string $STATUS_DESCRIPTION;
    public bool $DEPOSITED;
    public int $ACCOUNTS_RECEIVABLE_ID;
    public $paymentMethodList = [];
    public bool $showCardNo = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo = false;
    public bool $showReceiptDate = false;
    public bool $showFileName = false;

    public int $PAYMENT_TYPE;
    private $systemSettingServices;
    private $paymentMethodServices;
    private $accountServices;
    private $paymentServices;
    private $invoiceServices;
    public function boot(
        PaymentServices $paymentServices,
        InvoiceServices $invoiceServices,
        PaymentMethodServices $paymentMethodServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->invoiceServices = $invoiceServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
    }
    public function mount()
    {
        $this->PAYMENT_METHOD_ID = (int) $this->systemSettingServices->GetValue('DefaultPaymentMethodId');
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivable');
        $this->ID = 0;
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->CODE = '';
        $this->NOTES = '';

        $this->CARD_NO = '';
        $this->CARD_EXPIRY_DATE = null;
        $this->RECEIPT_REF_NO = '';
        $this->RECEIPT_DATE = null;

        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $this->OVERPAYMENT_ACCOUNT_ID = 0;
    }
    public function updatedAMOUNT()
    {
        $this->AMOUNT_APPLIED = $this->AMOUNT;
    }
    public function save()
    {


        $getType = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);
        $this->PAYMENT_TYPE = (int) $getType->PAYMENT_TYPE;
        if ($this->PAYMENT_TYPE == 10) {
            $this->validate(
                [
                    'PDF' => 'required',
                    'AMOUNT' => 'required|not_in:0',
                    'AMOUNT_APPLIED' => 'required|not_in:0',
                ],
                [],
                [

                    'PDF' => 'Pdf document file',
                    'AMOUNT' => 'Amount',
                    'AMOUNT_APPLIED' => 'Amount applied',
                ]
            );

        } else {
            $this->validate(
                [

                    'AMOUNT' => 'required|not_in:0',
                    'AMOUNT_APPLIED' => 'required|not_in:0',
                ],
                [],
                [
                    'AMOUNT' => 'Amount',
                    'AMOUNT_APPLIED' => 'Amount applied',
                ]
            );
        }


        if ($this->AMOUNT < $this->AMOUNT_APPLIED) {
            session()->flash('error', 'Invalid amount applied');
            return;
        }

        $balance = (float) $this->invoiceServices->getBalance($this->INVOICE_ID);
        if ($balance < $this->AMOUNT_APPLIED) {
            session()->flash('error', 'Amount applied is to high from balance');
            return;
        }

        if ($this->storeMode()) {

            $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);
            $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
            $this->dispatch('update-amount', result: $getResult);
            $this->dispatch('update-status');
            $this->dispatch('payment-modal-close');
        }


    }
    public function storeMode(): bool
    {
        try {
            \DB::beginTransaction();
            if ($this->ID == 0) {
                $this->ID = $this->paymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->AMOUNT_APPLIED,
                    $this->PAYMENT_METHOD_ID,
                    $this->CARD_NO,
                    $this->CARD_EXPIRY_DATE,
                    $this->RECEIPT_REF_NO,
                    $this->RECEIPT_DATE,
                    $this->NOTES,
                    $this->UNDEPOSITED_FUNDS_ACCOUNT_ID,
                    $this->OVERPAYMENT_ACCOUNT_ID,
                    0,
                    $this->ACCOUNTS_RECEIVABLE_ID
                );

                $this->paymentServices->PaymentInvoiceStore($this->ID, $this->INVOICE_ID, 0, $this->AMOUNT_APPLIED, 0, 0);

                if ($this->PAYMENT_TYPE == 10) {
                    $tempPath = $this->PDF->store('public/temp', 'public');
                    // Generate a random filename
                    $randomFilename = Str::random(40); // Generate a random string of 40 characters             
                    // Get the file extension
                    $extension = $this->PDF->extension();
                    // Construct the new file path with the random filename and original extension
                    $newPath = 'payment/' . $randomFilename . '.' . $extension;
                    // Move the temporary file to the new path
                    Storage::disk('public')->move($tempPath, $newPath);
                    // Update the database record with the new filename
                    $this->paymentServices->UpdateFile($this->ID, $randomFilename . '.' . $extension, $newPath);

                }

                \DB::commit();







                return true;
            }
            return false;
        } catch (\Exception $e) {
            \DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }
    public function updatedPdf()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }
    public function updatedpaymentmethodid()
    {
        $paymentMethod = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);

        if ($paymentMethod) {

            switch ($paymentMethod->PAYMENT_TYPE) {
                case 0:
                    $this->showCardNo = false;
                    $this->showCardDateExpire = false;
                    $this->showReceiptNo = false;
                    $this->showReceiptDate = false;
                    $this->showFileName = false;
                    break;
                case 1:
                    $this->showCardNo = false;
                    $this->showCardDateExpire = false;
                    $this->showReceiptNo = true;
                    $this->showReceiptDate = true;
                    $this->showFileName = false;
                    break;
                case 4:
                    $this->showCardNo = true;
                    $this->showCardDateExpire = true;
                    $this->showReceiptNo = true;
                    $this->showReceiptDate = false;
                    $this->showFileName = false;
                    break;
                case 5:
                    $this->showCardNo = true;
                    $this->showCardDateExpire = true;
                    $this->showReceiptNo = true;
                    $this->showReceiptDate = false;
                    $this->showFileName = false;
                    break;
                case 8:
                    $this->showCardNo = false;
                    $this->showCardDateExpire = false;
                    $this->showReceiptNo = false;
                    $this->showReceiptDate = false;
                    $this->showFileName = false;
                    break;
                case 9:
                    $this->showCardNo = false;
                    $this->showCardDateExpire = false;
                    $this->showReceiptNo = false;
                    $this->showReceiptDate = false;
                    $this->showFileName = false;
                    break;
                default:
                    # code...
                    $this->showCardNo = false;
                    $this->showCardDateExpire = false;
                    $this->showReceiptNo = false;
                    $this->showReceiptDate = false;
                    $this->showFileName = true;
                    break;
            }
        }



    }

    public function render()
    {
        $this->paymentMethodList = $this->paymentMethodServices->getList();
        return view('livewire.service-charge.payment-modal-new-entry');
    }
}
