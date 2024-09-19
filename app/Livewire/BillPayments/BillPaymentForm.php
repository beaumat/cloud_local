<?php

namespace App\Livewire\BillPayments;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\BillPaymentServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bill Payments')]
class BillPaymentForm extends Component
{
    public int $ID;
    public string $CODE;
    public bool $UNPOSTED = true;
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
    private $accountServices;
    private $documentStatusServices;
    private $objectServices;
    private $accountJournalServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices,
        ObjectServices $objectServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountServices = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->objectServices = $objectServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    #[On('reset-payment')]
    public function ResetPaymentApplied()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->UpdateBillPaymentApplied($this->ID);
    }
    private function LoadDropDown()
    {
        $this->contactList = $this->contactServices->getList(0);
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->billPaymentServices->Get($id);
            if ($data) {
                $this->LoadDropDown();
                $this->getInfo($data);

                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorsbill_payment')->with('error', $errorMessage);
        }
        $this->LoadDropDown();
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->AMOUNT = 0;
        $this->NOTES = '';
        $this->BANK_ACCOUNT_ID = 0;
        $this->PAY_TO_ID = 0;
        $this->Modify = true;
        $this->AMOUNT_APPLIED = 0;
    }
    public function getInfo($data)
    {
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
                        'BANK_ACCOUNT_ID'   => 'required|not_in:0',
                        'PAY_TO_ID'         => 'required|not_in:0',
                        'AMOUNT'            => 'required|not_in:0',
                        'DATE'              => 'required',
                        'LOCATION_ID'       => 'required'

                    ],
                    [],
                    [
                        'PAY_TO_ID'         => 'Pay To',
                        'BANK_ACCOUNT_ID'   => 'Bank Account',
                        'DATE'              => 'Date',
                        'LOCATION_ID'       => 'Location',
                        'AMOUNT'            => 'Amount'
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
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->NOTES

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

    public function getPosted()
    {
        try {
            DB::beginTransaction();
            $check = $this->billPaymentServices->object_type_check;
            $checkbills = $this->billPaymentServices->object_type_check_bills;
            $JOURNAL_NO  = (int) $this->accountJournalServices->getRecord($check, $this->ID);
            if ($JOURNAL_NO  == 0) {
                $JOURNAL_NO = (int) $this->accountJournalServices->getJournalNo($check, $this->ID) + 1;
            }

            $checkDataBills = $this->billPaymentServices->billPaymentBillsJournal($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkDataBills,
                $this->LOCATION_ID,
                $checkbills,
                $this->DATE,
                "AP"
            );
            $checkData = $this->billPaymentServices->billPaymentJournalRemaining($this->ID);
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkData,
                $this->LOCATION_ID,
                $check,
                $this->DATE,
                "BILL"
            );




            $checkData = $this->billPaymentServices->billPaymentJournal($this->ID);
            
            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $checkData,
                $this->LOCATION_ID,
                $check,
                $this->DATE,
                "BILL"
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
            $debit_sum = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum) {
                $this->billPaymentServices->StatusUpdate($this->ID, 15);
                DB::commit();
                $data = $this->billPaymentServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                    return;
                }
                session()->flash('message', 'Successfully posted');
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->billPaymentServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('vendorsbill_payment_edit', $this->ID)->with('message', 'Successfully unposted');
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function OpenJournal()
    {

        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billPaymentServices->object_type_check, $this->ID);
        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
        }
    }
    public function render()
    {
        $this->AMOUNT_APPLIED = (float) $this->billPaymentServices->getTotalApplied($this->ID);

        return view('livewire.bill-payments.bill-payment-form');
    }
}
