<?php

namespace App\Livewire\Payment;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\SystemSettingServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;

#[Title('Payments')]
class PaymentForm extends Component
{
    public int $ID;
    public string $CODE;
    public $DATE;
    public int $CUSTOMER_ID;
    public int $LOCATION_ID;
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
    public $locationList = [];
    public $contactList = [];
    public $paymentMethodList = [];
    private $paymentServices;
    private $locationServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $accountServices;
    private $paymentMethodServices;
    private $contactServices;
    public bool $Modify = true;
    public bool $showCardNo = false;
    public bool $showCardDateExpire = false;
    public bool $showReceiptNo = false;
    public bool $showReceiptDate = false;
    public bool $showFileName = false;
    private $uploadServices;
    private $dateServices;
    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        ContactServices $contactServices,
        UploadServices $uploadServices,
        DateServices $dateServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->contactServices = $contactServices;
        $this->uploadServices = $uploadServices;
        $this->dateServices = $dateServices;

    }
    public function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->DATE = $data->DATE;
        $this->CODE = $data->CODE;
        $this->CUSTOMER_ID = $data->CUSTOMER_ID;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->AMOUNT = $data->AMOUNT;
        $this->AMOUNT_APPLIED = $data->AMOUNT_APPLIED;
        $this->PAYMENT_METHOD_ID = $data->PAYMENT_METHOD_ID;
        $this->CARD_NO = $data->CARD_NO ?? null;
        $this->CARD_EXPIRY_DATE = $data->CARD_EXPIRY_DATE ?? null;
        $this->RECEIPT_REF_NO = $data->RECEIPT_REF_NO ?? null;
        $this->RECEIPT_DATE = $data->RECEIPT_DATE ?? null;
        $this->NOTES = $data->NOTES ?? null;
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = $data->UNDEPOSITED_FUNDS_ACCOUNT_ID ?? 0;
        $this->OVERPAYMENT_ACCOUNT_ID = $data->OVERPAYMENT_ACCOUNT_ID ?? 0;
        $this->ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DATE = $data->STATUS_DATE ?? null;
        $this->DEPOSITED = $data->DEPOSITED ?? null;
        $this->updatedpaymentmethodid();
        $this->Modify = false;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->paymentServices->UpdatePaymentApplied($this->ID);
    }
    public function mount($id = null)
    {
        $this->contactList = $this->contactServices->getList(1);
        $this->locationList = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();

        if (is_numeric($id)) {
            $data = $this->paymentServices->get($id);
            if ($data) {
                $this->getInfo($data);
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('customerspayment')->with('error', $errorMessage);
        }


        $this->ID = 0;
        $this->DATE = $this->dateServices->NowDate();
        $this->CODE = '';
        $this->CUSTOMER_ID = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->AMOUNT = 0;
        $this->AMOUNT_APPLIED = 0;
        $this->PAYMENT_METHOD_ID = 0;
        $this->CARD_NO = '';
        $this->CARD_EXPIRY_DATE = null;
        $this->RECEIPT_REF_NO = '';
        $this->RECEIPT_DATE = null;
        $this->NOTES = '';
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $this->OVERPAYMENT_ACCOUNT_ID = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivable');
        $this->STATUS = 0;
        $this->DEPOSITED = 0;
        $this->Modify = true;
        $this->updatedpaymentmethodid();
    }
    public function updatedPdf()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }
    public function save()
    {

        $this->validate(
            [
                'CUSTOMER_ID' => 'required|not_in:0',
                'PAYMENT_METHOD_ID' => 'required|not_in:0',
                'DATE' => 'required',
                'LOCATION_ID' => 'required',
                'AMOUNT' => 'required|not_in:0',
            ],
            [],
            [
                'CUSTOMER_ID' => 'Customer',
                'PAYMENT_METHOD_ID' => 'Payment Method',
                'DATE' => 'Date',
                'LOCATION_ID' => 'Location',
                'AMOUNT' => 'Amount',
            ]
        );

        try {

            if ($this->ID == 0) {

                $this->ID = $this->paymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    0,
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


                return Redirect::route('customerspayment_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->paymentServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->CUSTOMER_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
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
                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updateCancel()
    {
        $data = $this->paymentServices->get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
        $this->Modify = false;

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
                    $this->showReceiptNo = true;
                    $this->showReceiptDate = true;
                    $this->showFileName = true;
                    break;
            }
        }


    }
    public function render()
    {
        return view('livewire.payment.payment-form');
    }
}
