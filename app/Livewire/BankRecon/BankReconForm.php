<?php

namespace App\Livewire\BankRecon;

use App\Services\AccountServices;
use App\Services\BankReconServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Reconciliation')]
class BankReconForm extends Component
{
    public int $openStatus = 0;
    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $ACCOUNT_ID;
    public int $LOCATION_ID;
    public int $PREVIOUS_ID;
    public int $SEQUENCE_NO;
    public float $BEGINNING_BALANCE;
    public float $CLEARED_DEPOSITS;
    public float $CLEARED_WITHDRAWALS;
    public float $CLEARED_BALANCE;
    public float $ENDING_BALANCE;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DATE;

    public int $SC_ACCOUNT_ID;
    public int $IE_ACCOUNT_ID;
    public float $SC_RATE;
    public float $IE_RATE;

    public $sc_accountList =  [];
    public $ie_accountList = [];


    public $accountList = [];
    public $locationList = [];
    public bool $Modify = true;
    private $bankReconServices;
    private $userServices;
    private $locationServices;
    private $accountServices;
    public function boot(
        BankReconServices $bankReconServices,
        UserServices $userServices,
        LocationServices $locationServices,
        AccountServices $accountServices
    ) {
        $this->bankReconServices = $bankReconServices;
        $this->userServices = $userServices;
        $this->accountServices = $accountServices;
        $this->locationServices = $locationServices;
    }
    private function dropDownLoad()
    {
        $this->accountList = $this->accountServices->getBankAccount();
        $this->locationList = $this->locationServices->getList();
        $this->sc_accountList = $this->accountServices->getExpenses();
        $this->ie_accountList = $this->accountServices->getIncome();

    }
    private function getInfo($data)
    {

        $this->ID  = $data->ID;
        $this->DATE = $data->DATE;
        $this->CODE = $data->CODE ?? 0;
        $this->ACCOUNT_ID = $data->ACCOUNT_ID ?? 0;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->PREVIOUS_ID = $data->PREVIOUS_ID ?? 0;
        $this->SEQUENCE_NO = $data->SEQUENCE_NO ?? 0;
        $this->BEGINNING_BALANCE = $data->BEGINNING_BALANCE ?? 0;
        $this->CLEARED_DEPOSITS  = $data->CLEARED_DEPOSITS ?? 0;
        $this->CLEARED_WITHDRAWALS  = $data->CLEARED_WITHDRAWALS ?? 0;
        $this->CLEARED_BALANCE  = $data->CLEARED_BALANCE ?? 0;
        $this->ENDING_BALANCE = $data->ENDING_BALANCE ?? 0;
        $this->NOTES = $data->NOTES ?? '';
        $this->STATUS = $data->STATUS ?? 0;
    }
    public function mount($id = null)
    {
        if (is_numeric($id)) {
            $data = $this->bankReconServices->get($id);
            if ($data) {
                $this->dropDownLoad();
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('bankingbank_recon')->with('error', $errorMessage);
        }

        $this->dropDownLoad();
        $this->ID  = 0;
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->CODE = '';
        $this->ACCOUNT_ID = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->PREVIOUS_ID = 0;
        $this->SEQUENCE_NO = 0;
        $this->BEGINNING_BALANCE = 0;
        $this->CLEARED_DEPOSITS  = 0;
        $this->CLEARED_WITHDRAWALS  = 0;
        $this->CLEARED_BALANCE  = 0;
        $this->ENDING_BALANCE =  0;
        $this->NOTES =  '';
        $this->STATUS = 0;
        $this->Modify = true;
    }
    public function save()
    {

        $this->validate(
            [
                'ACCOUNT_ID'        => 'required|not_in:0|exists:account,id',
                'CODE'              =>  $this->ID > 0 ? 'required|max:20|unique:account_reconciliation,code,' . $this->ID : 'nullable',
                'DATE'              => 'required',
                'LOCATION_ID'       => 'required|exists:location,id'

            ],
            [],
            [
                'ACCOUNT_ID' => 'Bank Account',
                'CODE'       => 'Reference No.',
                'DATE'       => 'Date',
                'LOCATION_ID' => 'Location'

            ]
        );


        try {

            if ($this->ID == 0) {
                $this->ID = $this->bankReconServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->ACCOUNT_ID,
                    $this->LOCATION_ID,
                    $this->PREVIOUS_ID,
                    $this->SEQUENCE_NO,
                    $this->BEGINNING_BALANCE,
                    $this->CLEARED_DEPOSITS,
                    $this->CLEARED_WITHDRAWALS,
                    $this->CLEARED_BALANCE,
                    $this->NOTES,
                    $this->SC_ACCOUNT_ID,
                    $this->SC_RATE,
                    $this->IE_ACCOUNT_ID,
                    $this->IE_RATE
                );

                return Redirect::route('bankingbank_recon_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->bankReconServices->Update(
                    $this->ID,
                    $this->DATE,
                    $this->CODE,
                    $this->NOTES,
                    $this->SC_ACCOUNT_ID,
                    $this->SC_RATE,
                    $this->IE_ACCOUNT_ID,
                    $this->IE_RATE
                );
                session()->flash('message', 'Successfully updated');
                $this->Modify = false;
            }
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
    public function getPosted() {}
    public function render()
    {

        return view('livewire.bank-recon.bank-recon-form');
    }
}
