<?php

namespace App\Livewire\SpendMoney;

use App\Services\AccountServices;
use App\Services\SpendMoneyServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SpendMoneyDetails extends Component
{
    #[Reactive()]
    public int $STATUS;
    #[Reactive()]
    public int $LOCATION_ID;

    #[Reactive()]
    public int $SPEND_MONEY_ID;
    public int $ACCOUNT_ID = 0;
    public float $AMOUNT = 0.0;
    public string $NOTES = '';

    public $editID = null;
    public int $editACCOUNT_ID = 0;
    public float $editAMOUNT = 0.0;
    public string $editNOTES = '';


    public $dataList = [];
    public $accountList = [];
    public bool $saveSuccess = false;
    public bool $codeBase = false;

    public float $TOTAL_AMOUNT = 0.0;

    public $acctDescList = [];
    public string $ACCOUNT_CODE;
    private $spendMoneyServices;
    private $accountServices;
    public function boot(SpendMoneyServices $spendMoneyServices, AccountServices $accountServices)
    {

        $this->spendMoneyServices = $spendMoneyServices;
        $this->accountServices = $accountServices;
    }
    public function mount()
    {
        $this->LoadDropdown();
    }
    private function LoadDropdown()
    {
        if ($this->codeBase) {
            $this->accountList = $this->accountServices->getAccount(true);
        } else {
            $this->acctDescList = $this->accountServices->getAccount(false);
        }


    }
    public function Store()
    {

        $this->spendMoneyServices->StoreDetails(
            $this->SPEND_MONEY_ID,
            $this->ACCOUNT_ID,
            $this->AMOUNT,
            $this->NOTES
        );


    }
    public function Edit(int $ID)
    {
        $data = $this->spendMoneyServices->getDetails($ID);

        if ($data) {
            $this->editID = $data->ID;
            $this->editACCOUNT_ID = $data->ACCOUNT_ID;
            $this->editAMOUNT = $data->AMOUNT;
            $this->editNOTES = $data->NOTES;
        }
    }
    public function Update()
    {
        $this->spendMoneyServices->UpdateDetails(
            $this->editID,
            $this->editACCOUNT_ID,
            $this->editAMOUNT,
            $this->editNOTES
        );
        $this->editID = null;
    }
    public function Delete(int $ID)
    {
        $this->spendMoneyServices->DeleteDetails($ID);
    }
    public function render()
    {
        $this->dataList = $this->spendMoneyServices->getDetailsList($this->SPEND_MONEY_ID);
        return view('livewire.spend-money.spend-money-details');
    }
}
