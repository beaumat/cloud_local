<?php

namespace App\Livewire\WriteCheck;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use App\Services\WriteCheckServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Direct Pay')]
class WriteCheckForm extends Component
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
    private $writeCheckServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    private $documentStatusServices;

    private $accountJournalServices;
    public function boot(
        WriteCheckServices $writeCheckServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices,
        AccountJournalServices $accountJournalServices
    ) {
        $this->writeCheckServices = $writeCheckServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountServices = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->accountJournalServices = $accountJournalServices;
    }

    private function LoadDropDown()
    {
        $this->contactList = $this->contactServices->getListAllType();
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        if (is_numeric($id)) {
            $data = $this->writeCheckServices->Get($id);
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
        $this->NOTES = $data->NOTES ?? '';
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
        $data = $this->writeCheckServices->Get($this->ID);
        if ($data) {
            $this->getInfo($data);
        }
    }
    public function save()
    {

        $this->validate(
            [
                'BANK_ACCOUNT_ID'   => 'required|not_in:0|exists:account,id',
                'PAY_TO_ID'         => 'required|not_in:0|exists:contact,id',
                'DATE'              => 'required',
                'LOCATION_ID'       => 'required|exists:location,id'

            ],
            [],
            [
                'PAY_TO_ID'         => 'Pay To',
                'BANK_ACCOUNT_ID'   => 'Bank Account',
                'DATE'              => 'Date',
                'LOCATION_ID'       => 'Location',           
            ]
        );
        try {
            if ($this->ID == 0) {
               

                DB::beginTransaction();
                $this->ID = $this->writeCheckServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->PAY_TO_ID,
                    $this->LOCATION_ID,
                    $this->AMOUNT,
                    $this->NOTES,
                    $this->ACCOUNTS_PAYABLE_ID
                );
                DB::commit();
                return Redirect::route('vendorsbill_payment_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->validate(
                    [
                        'PAY_TO_ID'             => 'required|not_in:0|exists:contact,id',
                        'BANK_ACCOUNT_ID'       => 'required|not_in:0|exists:account,id',
                        'CODE'                  => 'required|max:20|unique:bill,code,' . $this->ID,
                        'DATE'                  => 'required',
                        'LOCATION_ID'           => 'required',
                        'AMOUNT'                => 'required|not_in:0'
                    ],
                    [],
                    [
                        'PAY_TO_ID'             => 'Pay To',
                        'BANK_ACCOUNT_ID'       => 'Bank Account',
                        'CODE'                  => 'Reference No.',
                        'DATE'                  => 'Date',
                        'LOCATION_ID'           => 'Location',
                        'AMOUNT'                => 'Amount'
                    ]
                );
                DB::beginTransaction();
                $data =  $this->writeCheckServices->Get($this->ID);
                if ($data) {
                    if ($this->STATUS == 16) {
                        $JNO = $this->accountJournalServices->getRecord($this->writeCheckServices->object_type_check, $this->ID);
                        if ($JNO > 0) {
                            // BANK_ACCOUNT_ID on CREDIT 
                            $this->accountJournalServices->AccountSwitch(
                                $this->BANK_ACCOUNT_ID,
                                $data->BANK_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->PAY_TO_ID,
                                $this->ID,
                                $this->writeCheckServices->object_type_check,
                                $this->DATE,
                                1
                            );
                            // BANK_ACCOUNT_ID on DEBIT 
                            $this->accountJournalServices->AccountSwitch(
                                $this->BANK_ACCOUNT_ID,
                                $data->BANK_ACCOUNT_ID,
                                $this->LOCATION_ID,
                                $JNO,
                                $data->PAY_TO_ID,
                                $this->ID,
                                $this->writeCheckServices->object_type_check,
                                $this->DATE,
                                0
                            );
                        }
                    }

                    $this->writeCheckServices->Update(
                        $this->ID,
                        $this->CODE,
                        $this->BANK_ACCOUNT_ID,
                        $this->PAY_TO_ID,
                        $this->LOCATION_ID,
                        $this->AMOUNT,
                        $this->NOTES

                    );

                    DB::commit();

                    session()->flash('message', 'Successfully updated');
                }
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            DB::rollBack();
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
        return view('livewire.write-check.write-check-form');
    }
}
