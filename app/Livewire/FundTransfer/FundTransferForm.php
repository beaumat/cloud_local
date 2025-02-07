<?php

namespace App\Livewire\FundTransfer;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\FundTransferServices;
use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Fund Transfer')]
class FundTransferForm extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $FROM_LOCATION_ID;
    public int $TO_LOCATION_ID;

    public int $FROM_ACCOUNT_ID;
    public int $TO_ACCOUNT_ID;


    public int $FROM_NAME_ID;
    public int $TO_NAME_ID;


    public float $AMOUNT;


    public string $NOTES;

    public $fromLocationList = [];
    public $toLocationList = [];

    public $fromContactList = [];
    public $toContactList = [];

    public $fromAccountList = [];
    public $toAccountList = [];

    public bool $Modify;
    private $fundTransferServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $accountJournalServices;
    private $contactServices;
    private $accountServices;
    public function boot(
        FundTransferServices $fundTransferServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices,
        ContactServices $contactServices,
        AccountServices $accountServices


    ) {

        $this->fundTransferServices = $fundTransferServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->contactServices = $contactServices;
        $this->accountServices = $accountServices;
    }
    public function LoadDropdown()
    {
        $this->fromLocationList = $this->locationServices->getList();
        $this->toLocationList = $this->locationServices->getList();

        $this->fromContactList = $this->contactServices->getListAllType();
        $this->toContactList = $this->contactServices->getListAllType();

        $this->fromAccountList = $this->accountServices->getAccount(false);
        $this->toAccountList = $this->accountServices->getAccount(false);
    }

    // public function AccountJournal(): bool
    // {
    //     try {


    //         $JOURNAL_NO = $this->accountJournalServices->getRecord($this->fundTransferServices->object_type_id, $this->ID);
    //         if ($JOURNAL_NO  == 0) {
    //             $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->fundTransferServices->object_type_id, $this->ID) + 1;
    //         }

    //         //Main
    //         $generalJournalData = $this->fundTransferServices->getGeneralJournalEntries($this->ID);

    //         $this->accountJournalServices->JournalExecute(
    //             $JOURNAL_NO,
    //             $generalJournalData,
    //             $this->LOCATION_ID,
    //             $generaljournal,
    //             $this->DATE
    //         );

    //         $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

    //         $debit_sum = (float) $data['DEBIT'];
    //         $credit_sum = (float) $data['CREDIT'];

    //         if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
    //             return true;
    //         }
    //         session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
    //         return false;
    //     } catch (\Exception $e) {
    //         $errorMessage = 'Error occurred: ' . $e->getMessage();
    //         session()->flash('error', $errorMessage);
    //         return false;
    //     }
    // }

    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
    }
    public function mount($id = null)
    {


        if (is_numeric($id)) {
            $data = $this->fundTransferServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingfund_transfer')->with('error', $errorMessage);
        }

        $this->LoadDropdown();
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->FROM_ACCOUNT_ID = 0;
        $this->TO_ACCOUNT_ID = 0;

        $this->FROM_LOCATION_ID = $this->userServices->getLocationDefault();
        $this->TO_LOCATION_ID = 0;

        $this->FROM_NAME_ID = 0;
        $this->TO_NAME_ID = 0;
        $this->AMOUNT = 0;


        $this->NOTES = '';

        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {

        // 'CODE'          =>  $this->ID > 0 ? 'required|max:20|unique:general_journal,code,' . $this->ID : 'nullable',
        $this->validate(
            [
                'CODE'                  => 'nullable|max:20|unique:fund_transfer,code,' . ($this->ID > 0 ? $this->ID : 'NULL') . ',id',
                'DATE'                  => 'required|date',
                'FROM_LOCATION_ID'      => 'required|exists:location,id',
                'TO_LOCATION_ID'        => 'required|exists:location,id',
                'FROM_ACCOUNT_ID'       => 'required|exists:account,id',
                'TO_ACCOUNT_ID'         => 'required|exists:account,id',
                'FROM_NAME_ID'          =>  $this->FROM_NAME_ID  > 0 ? 'exists:contact,id' : 'nullable',
                'TO_NAME_ID'            =>  $this->TO_NAME_ID  > 0 ? 'exists:contact,id' : 'nullable',
                'AMOUNT'                => 'required|numeric|not_in:0'
            ],
            [],
            [
                'CODE'                  => 'Reference No.',
                'DATE'                  => 'Date',
                'FROM_LOCATION_ID'      => 'From Location',
                'TO_LOCATION_ID'        => 'To Location',
                'FROM_ACCOUNT_ID'       => 'From Account',
                'TO_ACCOUNT_ID'         => 'To Account',
                'FROM_NAME_ID'          => 'From Name',
                'TO_NAME_ID'            => 'To Name',
                'AMOUNT'                => 'Amount Fund',  
            ]
        );


        try {
            if ($this->ID == 0) {

                $this->ID = $this->fundTransferServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->FROM_ACCOUNT_ID,
                    $this->TO_ACCOUNT_ID,
                    $this->FROM_NAME_ID,
                    $this->TO_NAME_ID,
                    $this->FROM_LOCATION_ID,
                    $this->TO_LOCATION_ID,
                    0,
                    $this->NOTES,
                    $this->AMOUNT
                );

                return Redirect::route('bankingfund_transfer_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->fundTransferServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->FROM_ACCOUNT_ID,
                    $this->TO_ACCOUNT_ID,
                    $this->FROM_NAME_ID,
                    $this->TO_NAME_ID,
                    $this->FROM_LOCATION_ID,
                    $this->TO_LOCATION_ID,
                    0,
                    $this->NOTES,
                    $this->AMOUNT

                );
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updateCancel()
    {
        return Redirect::route('bankingfund_transfer_edit', ['id' => $this->ID]);
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function posted()
    {
        try {
            $total_result = $this->fundTransferServices->GetTotal($this->ID);
            $total_debit = (float) $total_result['TOTAL_DEBIT'];
            $total_credit = (float) $total_result['TOTAL_CREDIT'];

            if ($total_debit == 0) {
                Session()->flash('error', 'No debit entry');
                return;
            }
            if ($total_credit == 0) {
                Session()->flash('error', 'No credit entry');
                return;
            }

            if ($total_debit == $total_credit) {
                DB::beginTransaction();
                if (!$this->AccountJournal()) {
                    DB::rollBack();
                    return;
                }

                DB::commit();

                $data = $this->fundTransferServices->get($this->ID);
                if ($data) {
                    $this->getInfo($data);
                    $this->Modify = false;
                }

                Session()->flash('message', 'Successfully posted');
                return;
            }

            Session()->flash('error', 'Invalid disbalanced.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function print() {}
    public function OpenJournal()
    {
        $FirstID = $this->fundTransferServices->getFirstDetailsID($this->ID);
        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->fundTransferServices->object_type_general_journal_details_id,
            $FirstID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }

    public function getUnposted()
    {
        try {
            DB::beginTransaction();
            $this->fundTransferServices->StatusUpdate($this->ID, 16);
            DB::commit();
            Redirect::route('companygeneral_journal_edit', $this->ID);
        } catch (\Throwable $th) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $th->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.fund-transfer.fund-transfer-form');
    }
}
