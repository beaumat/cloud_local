<?php

namespace App\Livewire\ServiceCharge;

use App\Services\AccountServices;
use App\Services\PatientPaymentServices;
use App\Services\PaymentMethodServices;
use App\Services\ServiceChargeServices;
use App\Services\SystemSettingServices;
use App\Services\UploadServices;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithFileUploads;
class PaymentModalNewEntry extends Component
{

    use WithFileUploads;
    public $PDF;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $SERVICE_CHARGES_ID;
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
    private $patientPaymentServices;
    private $serviceChargeServices;
    private $uploadServices;
    public function boot(
        PatientPaymentServices $patientPaymentServices,
        ServiceChargeServices $serviceChargeServices,
        PaymentMethodServices $paymentMethodServices,
        SystemSettingServices $systemSettingServices,
        AccountServices $accountServices,
        UploadServices $uploadServices
    ) {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->accountServices = $accountServices;
        $this->uploadServices = $uploadServices;
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

        if ($this->patientPaymentServices->HaveRemainingPaymentBalance($this->PATIENT_ID, $this->LOCATION_ID)) {
            session()->flash('error', 'Invalid create. Patient have existing balance.');
            return;
        }

        if ($this->AMOUNT < $this->AMOUNT_APPLIED) {
            session()->flash('error', 'Invalid amount applied');
            return;
        }

        $balance = (float) $this->serviceChargeServices->getBalance($this->SERVICE_CHARGES_ID);
        if ($balance < $this->AMOUNT_APPLIED) {
            session()->flash('error', 'Amount applied is to high from balance');
            return;
        }

        if ($this->storeMode()) {

            $this->serviceChargeServices->updateServiceChargesBalance($this->SERVICE_CHARGES_ID);
            $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);       
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
                $this->ID = $this->patientPaymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->PATIENT_ID,
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

                $this->patientPaymentServices->PaymentChargeStore($this->ID, $this->SERVICE_CHARGES_ID, 0, $this->AMOUNT_APPLIED, 0, 0);

                if ($this->PAYMENT_TYPE == 10) {

                    $returnData = $this->uploadServices->Payment($this->PDF);
                 
                    $this->patientPaymentServices->UpdateFile($this->ID, $returnData['filename'] . '.' . $returnData['extension'], $returnData['new_path']);

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
                    $this->showReceiptNo = true;
                    $this->showReceiptDate = true;
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
