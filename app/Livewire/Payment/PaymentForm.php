<?php

namespace App\Livewire\Payment;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\PaymentMethodServices;
use App\Services\PaymentServices;
use App\Services\SystemSettingServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

#[Title('Payments')]
class PaymentForm extends Component
{
    use WithFileUploads;
    public $PDF;
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
    public string $FILE_NAME;
    public string $FILE_PATH;

    public function boot(
        PaymentServices $paymentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        PaymentMethodServices $paymentMethodServices,
        ContactServices $contactServices
    ) {
        $this->paymentServices = $paymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->contactServices = $contactServices;
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
        $this->FILE_NAME = $data->FILE_NAME ?? '';
        $this->FILE_PATH = $data->FILE_PATH ?? '';
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
        $this->contactList = $this->contactServices->getList(3);
        $this->locationList = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getList();

        if (is_numeric($id)) {
            $data = $this->paymentServices->get($id);
            if ($data) {
                $this->getInfo($data);
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('transactionspayment')->with('error', $errorMessage);
        }


        $this->ID = 0;
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->CODE = '';
        $this->CUSTOMER_ID = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->AMOUNT = 0;
        $this->AMOUNT_APPLIED = 0;
        $this->PAYMENT_METHOD_ID = (int) $this->systemSettingServices->GetValue('DefaultPaymentMethodId');
        $this->CARD_NO = '';
        $this->CARD_EXPIRY_DATE = null;
        $this->RECEIPT_REF_NO = '';
        $this->RECEIPT_DATE = null;
        $this->NOTES = '';
        $this->UNDEPOSITED_FUNDS_ACCOUNT_ID = 0;
        $this->OVERPAYMENT_ACCOUNT_ID = 0;
        $this->ACCOUNTS_RECEIVABLE_ID = (int) $this->accountServices->getByName('Accounts Receivable');
        $this->PDF = null;
        $this->STATUS = 0;
        $this->DEPOSITED = 0;
        $this->Modify = true;
        $this->FILE_NAME = '';
        $this->FILE_PATH = '';
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

        $getType = $this->paymentMethodServices->get($this->PAYMENT_METHOD_ID);
        $PAYMENT_TYPE = (int) $getType->PAYMENT_TYPE;

        if ($PAYMENT_TYPE == 10 && $this->ID == 0) {
            $this->validate(
                [
                    'CUSTOMER_ID' => 'required|not_in:0',
                    'DATE' => 'required',
                    'PDF' => 'required',
                    'LOCATION_ID' => 'required',
                    'AMOUNT' => 'required|not_in:0',
                ],
                [],
                [
                    'CUSTOMER_ID' => 'Patient',
                    'DATE' => 'Date',
                    'PDF' => 'Pdf document file',
                    'LOCATION_ID' => 'Location',
                    'AMOUNT' => 'Amount',
                ]
            );

        } else {
            $this->validate(
                [
                    'CUSTOMER_ID' => 'required|not_in:0',
                    'DATE' => 'required',
                    'LOCATION_ID' => 'required',
                    'AMOUNT' => 'required|not_in:0',
                ],
                [],
                [
                    'CUSTOMER_ID' => 'Patient',
                    'DATE' => 'Date',
                    'LOCATION_ID' => 'Location',
                    'AMOUNT' => 'Amount',
                ]
            );
        }

        try {

            if ($this->ID == 0) {

                if ($this->paymentServices->HaveRemainingPaymentBalance($this->CUSTOMER_ID, $this->LOCATION_ID)) {
                    session()->flash('error', 'Invalid create. Patient have existing balance.');
                    return;
                }

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

                if ($PAYMENT_TYPE == 10) {
                    $this->getDocumentProccess();
                }

                return Redirect::route('transactionspayment_edit', ['id' => $this->ID])->with('message', 'Successfully created');

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

                if ($this->PDF) {
                    if ($PAYMENT_TYPE == 10) {
                        if (Storage::disk('public')->exists($this->FILE_PATH)) {
                            Storage::disk('public')->delete($this->FILE_PATH);
                        }

                        $this->getDocumentProccess();

                        $data = $this->paymentServices->get($this->ID);
                        if ($data) {
                            $this->getInfo($data);
                        }

                    }

                }
                $this->Modify = false;
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getDocumentProccess()
    {
        //Remove First
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
    public function getModify()
    {
        $this->PDF = null;
        $this->Modify = true;
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
                    $this->showReceiptNo = false;
                    $this->showReceiptDate = false;
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
