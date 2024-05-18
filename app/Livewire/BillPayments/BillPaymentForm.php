<?php

namespace App\Livewire\BillPayments;

use App\Services\AccountServices;
use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Payments')]
class BillPaymentForm extends Component
{
    public int $ID;
    public string $CODE;
    public $DATE;
    public int $PAY_TO_ID;
    public int $LOCATION_ID;
    public int $BANK_ACCOUNT_ID;
    public float $AMOUNT;
    public float $AMOUNT_APPLIED;
    public string $NOTES;
    public int $TYPE = 1;
    public int $STATUS = 0;
    public string $STATUS_DESCRIPTION;
    public int $ACCOUNTS_PAYABLE_ID = 21;
    public $locationList = [];
    public bool $Modify;
    public $contactList = [];
    public $accountList = [];
    private $billPaymentServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $accountServices;
    private $documentStatusServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->contactServices = $contactServices;
        $this->locationService = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->accountServices = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->UpdateBillPaymentApplied($this->ID);
    }
    public function mount($id = null)
    {
        $this->contactList = $this->contactServices->getList(0);
        $this->locationList = $this->locationService->getList();
        $this->accountList = $this->accountServices->getBankAccount();

        if (is_numeric($id)) {
            $data = $this->billPaymentServices->Get($id);
            if ($data) {
                $this->getInfo($data);

                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorsbill_payment')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->AMOUNT = 0;
        $this->NOTES = '';
        $this->BANK_ACCOUNT_ID = 0;
        $this->PAY_TO_ID = 0;
        $this->Modify = true;
        $this->AMOUNT_APPLIED = 0;
    }
    public function getInfo($data)
    { {
            $this->ID = $data->ID;
            $this->CODE = $data->CODE;
            $this->DATE = $data->DATE;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->AMOUNT = $data->AMOUNT;
            $this->NOTES = $data->NOTE ?? '';
            $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
            $this->PAY_TO_ID = $data->PAY_TO_ID;
            $this->STATUS = $data->STATUS;
            $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
            $this->Modify = false;

        }
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->billPaymentServices->Get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function save()
    {
        try {
            if ($this->ID == 0) {
                $this->validate(
                    [
                        'BANK_ACCOUNT_ID' => 'required|not_in:0',
                        'PAY_TO_ID' => 'required|not_in:0',
                        'AMOUNT' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required'

                    ],
                    [],
                    [
                        'PAY_TO_ID' => 'Pay To',
                        'BANK_ACCOUNT_ID' => 'Bank Account',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'AMOUNT' => 'Amount'
                    ]
                );
                $this->ID = $this->billPaymentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID
                );
                return Redirect::route('vendorsbill_payment_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->validate(
                    [
                        'PAY_TO_ID' => 'required|not_in:0',
                        'BANK_ACCOUNT_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:bill,code,' . $this->ID,
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'AMOUNT' => 'required|not_in:0'
                    ],
                    [],
                    [
                        'PAY_TO_ID' => 'Pay To',
                        'BANK_ACCOUNT_ID' => 'Bank Account',
                        'CODE' => 'Reference No.',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'AMOUNT' => 'Amount'
                    ]
                );

                $this->billPaymentServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->NOTES,

                );
                session()->flash('message', 'Successfully updated');
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->getTotalApplied($this->ID);


        return view('livewire.bill-payments.bill-payment-form');
    }
}
