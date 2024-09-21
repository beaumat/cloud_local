<?php

namespace App\Livewire\GeneralJournal;

use App\Services\AccountJournalServices;
use App\Services\DocumentStatusServices;
use App\Services\GeneralJournalServices;
use App\Services\LocationServices;
use App\Services\ObjectServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General Journal')]
class GeneralJournalForm extends Component
{

    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public bool $ADJUSTING_ENTRY;
    public string $NOTES;
    public $locationList = [];
    public bool $Modify;
    private $generalJournalServices;
    private $locationServices;
    private $userServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;

    private $objectServices;
    private $accountJournalServices;

    public function boot(
        GeneralJournalServices $generalJournalServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        ObjectServices $objectServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->generalJournalServices = $generalJournalServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->objectServices = $objectServices;
        $this->accountJournalServices = $accountJournalServices;
    }
    public function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
    }

    public function AccountJournal(): bool
    {

        try {

            $generaljournal = $this->generalJournalServices->object_type_general_journal_details_id;
            $getFirstId = (int) $this->generalJournalServices->getFirstDetailsID($this->ID);
            $JOURNAL_NO = $this->accountJournalServices->getRecord($generaljournal, $getFirstId);
            if ($JOURNAL_NO  == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($generaljournal, $getFirstId) + 1;
            }

            //Main
            $generalJournalData = $this->generalJournalServices->getGeneralJournalEntries($this->ID);

            $this->accountJournalServices->JournalExecute(
                $JOURNAL_NO,
                $generalJournalData,
                $this->LOCATION_ID,
                $generaljournal,
                $this->DATE
            );

            $data = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);

            $debit_sum = (float) $data['DEBIT'];
            $credit_sum = (float) $data['CREDIT'];

            if ($debit_sum == $credit_sum && $debit_sum > 0 && $credit_sum > 0) {
                return true;
            }
            session()->flash('error', 'debit:' . $debit_sum . ' and credit:' . $credit_sum . ' is not balance');
            return false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
            return false;
        }
    }

    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->NOTES = $data->NOTES ?? '';
        $this->ADJUSTING_ENTRY = $data->ADJUSTING_ENTRY ?? false;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {


        if (is_numeric($id)) {
            $data = $this->generalJournalServices->Get($id);
            if ($data) {
                $this->LoadDropdown();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companygeneral_journal')->with('error', $errorMessage);
        }
        $this->LoadDropdown();
        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->ADJUSTING_ENTRY = false;
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
        try {
            if ($this->ID == 0) {

                $this->validate(
                    [

                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',

                    ],
                    [],
                    [

                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',

                    ]
                );


                $this->ID = $this->generalJournalServices->Store(
                    $this->DATE,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ADJUSTING_ENTRY,
                    $this->NOTES

                );

                return Redirect::route('companygeneral_journal_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {

                $this->validate(
                    [

                        'CODE' => 'required|max:20|unique:general_journal,code,' . $this->ID,
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required'

                    ],
                    [],
                    [
                        'CODE' => 'Reference No.',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                    ]
                );


                $this->generalJournalServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ADJUSTING_ENTRY,
                    $this->NOTES
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
        $BA = $this->generalJournalServices->get($this->ID);
        if ($BA) {
            $this->getInfo($BA);
        }
        $this->Modify = false;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function OpenJournal()
    {
        $FirstID = $this->generalJournalServices->getFirstDetailsID($this->ID);

        $JOURNAL_NO = $this->accountJournalServices->getRecord(
            $this->generalJournalServices->object_type_general_journal_details_id,
            $FirstID
        );

        if ($JOURNAL_NO > 0) {
            $data = ['JOURNAL_NO' => $JOURNAL_NO];
            $this->dispatch('open-journal', result: $data);
            return;
        }

        session()->flash('error', 'Journal entry not created');
    }

    public function posted()
    {
        try {


            $total_result = $this->generalJournalServices->GetTotal($this->ID);

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


                $this->generalJournalServices->StatusUpdate($this->ID, 15);
                DB::commit();

                $data = $this->generalJournalServices->get($this->ID);
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
    public function getUnposted()
    {

        try {
            DB::beginTransaction();
            $this->generalJournalServices->StatusUpdate($this->ID, 16);
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
        return view('livewire.general-journal.general-journal-form');
    }
}
