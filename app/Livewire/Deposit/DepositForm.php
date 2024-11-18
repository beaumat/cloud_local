<?php

namespace App\Livewire\Deposit;

use App\Services\AccountServices;
use App\Services\DepositServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Bank Deposit')]
class DepositForm extends Component
{
    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $BANK_ACCOUNT_ID;
    public float $AMOUNT;
    public string $NOTES;
    public int $CASH_BACK_ACCOUNT_ID;
    public float $CASH_BACK_AMOUNT;
    public string $CASH_BACK_NOTES;
    public int $LOCATION_ID;
    public int $STATUS;
    public bool $Modify = true;
    public $STATUS_DESCRIPTION;
    public $accountList = [];
    public $locationList = [];
    private $depositServices;
    private $accountServices;
    private $locationServices;
    private $userServices;

    public function boot(DepositServices $depositServices, AccountServices $accountServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->depositServices = $depositServices;
        $this->accountServices = $accountServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    private function loadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getBankAccount();
    }
    public function mount($id = null)
    {

        $this->loadDropdown();

        if (is_numeric($id)) {

            $data = $this->depositServices->Get($id);
            if ($data) {

                $this->ID = $data->ID ?? 0;
                $this->DATE = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->STATUS = $data->STATUS;
                $this->BANK_ACCOUNT_ID = $data->BANK_ACCOUNT_ID;
                $this->AMOUNT = $data->AMOUNT ?? 0;
                $this->NOTES = $data->NOTES ?? '';
                $this->CASH_BACK_ACCOUNT_ID = $data->CASH_BACK_ACCOUNT_ID ?? 0;
                $this->CASH_BACK_AMOUNT  =  $data->CASH_BACK_AMOUNT ?? 0;
                $this->CASH_BACK_NOTES =  $data->CASH_BACK_NOTES ?? '';
            }

            return Redirect::route('bankingdeposit')->with('Record not found');
        }
        // New
        $this->ID =  0;
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->STATUS = 0;
        $this->BANK_ACCOUNT_ID = 0;
        $this->AMOUNT = 0;
        $this->NOTES = '';

        $this->CASH_BACK_ACCOUNT_ID = 0;
        $this->CASH_BACK_AMOUNT  =  0;
        $this->CASH_BACK_NOTES = '';
    }
    public function save()
    {

        $this->validate(
            [
                'DATE'              => 'required|date',
                'LOCATION_ID'       => 'required|numeric|exists:location,id',
                'BANK_ACCOUNT_ID'   => 'required|numeric|exists:account,id',
                'CODE'              =>  $this->ID > 0 ? 'required|max:20|unique:deposit,code,' . $this->ID : 'nullable',

            ],
            [],
            [
                'DATE'              => 'Date',
                'LOCATION_ID'       => 'Location',
                'BANK_ACCOUNT_ID'   => 'Bank Account',
                'CODE'              =>  'Reference No.',
            ]
        );

        try {
            if ($this->ID == 0) {

                $this->ID =  $this->depositServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->BANK_ACCOUNT_ID,
                    $this->NOTES,
                    $this->CASH_BACK_ACCOUNT_ID,
                    $this->CASH_BACK_AMOUNT,
                    $this->CASH_BACK_NOTES,
                    $this->LOCATION_ID
                );
    
                return Redirect::route('bankingdeposit_edit',['id' => $this->ID]);
    
            } else {
            }
        } catch (\Exception $e) {
                
        }
      
    }

    public function render()
    {
        return view('livewire.deposit.deposit-form');
    }
}
