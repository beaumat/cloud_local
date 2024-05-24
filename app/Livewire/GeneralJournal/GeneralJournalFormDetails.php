<?php

namespace App\Livewire\GeneralJournal;

use App\Services\AccountServices;
use App\Services\ClassServices;
use App\Services\GeneralJournalServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class GeneralJournalFormDetails extends Component
{
    #[Reactive]
    public int $GENERAL_JOURNAL_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $STATUS;
    public int $ACCOUNT_ID;
    public float $DEBIT;
    public float $CREDIT;
    public string $NOTES;
    public int $CLASS_ID;
    public $editId = null;
    public int $editAccountId;
    public float $editDebit;
    public float $editCredit;
    public string $editNotes;
    public int $editClassId;

    public $dataList = [];
    public $accountList = [];
    private $generalJournalServices;
    private $accountServices;
    private $classServices;

    public bool $saveSuccess = false;



    public bool $codeBase = false;
    public $acctDescList = [];
    public $acctCodeList = [];
    public $classList = [];

    public string $ACCOUNT_CODE;
    public string $ACCOUNT_DESCRIPTION;


    public function boot(GeneralJournalServices $generalJournalServices, AccountServices $accountServices, ClassServices $classServices)
    {
        $this->generalJournalServices = $generalJournalServices;
        $this->accountServices = $accountServices;
        $this->classServices = $classServices;
    }
    private function clearData()
    {
        $this->ACCOUNT_ID = 0;
        $this->DEBIT = 0;
        $this->CREDIT = 0;
        $this->NOTES = "";
        $this->CLASS_ID = 0;
        $this->ACCOUNT_CODE = '';
        $this->ACCOUNT_DESCRIPTION = '';
        $this->saveSuccess = $this->saveSuccess ? false : true;
    }
    public function updatedaccountid()
    {
        $acct = $this->accountServices->get($this->ACCOUNT_ID);

        if ($acct) {
            $this->ACCOUNT_CODE = $acct->TAG ? $acct->TAG : '';
            $this->ACCOUNT_DESCRIPTION = $acct->NAME;
            $this->NOTES = '';
        }
    }
    public function mount()
    {
        $this->clearData();
        $this->classList = $this->classServices->GetList();
        $this->updatedcodebase();
    }
    public function updatedcodebase()
    {
        if ($this->codeBase == true) {
            return $this->acctCodeList = $this->accountServices->getAccount(true);
        }
        return $this->acctDescList = $this->accountServices->getAccount(false);
    }
    public function save()
    {
        $debitAmount = 0;
        $creditAmount = 0;
        try {
            $debitAmount = (float) $this->DEBIT ?? 0;
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $creditAmount = (float) $this->CREDIT ?? 0;
        } catch (\Throwable $th) {
            //throw $th;
        }

        $this->validate(
            [
                'ACCOUNT_ID' => 'required',
            ],
            [],
            [
                'ACCOUNT_ID' => 'Account'
            ]
        );

        if ($debitAmount == 0 && $creditAmount == 0) {
            session()->flash('error', 'Invalid entry debit/credit. please enter amount');
            return;
        }

        if ($debitAmount != 0 && $creditAmount != 0) {
            session()->flash('error', 'Invalid entry debit/credit. please enter one value between debit or credit');
            return;
        }
        try {
            DB::beginTransaction();
            $this->generalJournalServices->StoreDetails($this->GENERAL_JOURNAL_ID, $this->ACCOUNT_ID, $debitAmount, $creditAmount, $this->NOTES, $this->CLASS_ID);
            $this->clearData();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }
    public function edit($id)
    {
        $data = $this->generalJournalServices->getDetails($id);

        if ($data) {
            $this->editId = $data->ID;
            $this->editDebit = $data->DEBIT;
            $this->editCredit = $data->CREDIT;
            $this->editNotes = $data->NOTES;
            $this->editClassId = $data->CLASS_ID ?? 0;
            $this->editAccountId = $data->ACCOUNT_ID ?? 0;
        }
    }
    public function update()
    {
        if ($this->editDebit == 0 && $this->editCredit == 0) {
            session()->flash('error', 'Invalid entry debit/credit. please enter value between debit or credit');
            return;
        }

        if ($this->editDebit != 0 && $this->editCredit != 0) {
            session()->flash('error', 'Invalid entry debit/credit. please enter one value between debit or credit');
            return;
        }

        try {
            $this->generalJournalServices->UpdateDetails($this->editId, $this->GENERAL_JOURNAL_ID, $this->editAccountId, $this->editDebit, $this->editCredit, $this->editNotes, $this->editClassId);
            $this->editId = null;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage() . '' . $e->getTraceAsString());
        }
    }
    public function editCancel()
    {
        $this->editId = null;
    }
    public function delete(int $id)
    {
        $this->generalJournalServices->DeleteDetails($id);
    }

    public function render()
    {

        $this->dataList = $this->generalJournalServices->ListDetails($this->GENERAL_JOURNAL_ID);
        $list = $this->generalJournalServices->GetTotal($this->GENERAL_JOURNAL_ID);
        return view('livewire.general-journal.general-journal-form-details', ['TOTAL_DEBIT' => $list['TOTAL_DEBIT'], 'TOTAL_CREDIT' => $list['TOTAL_CREDIT']]);
    }
}
