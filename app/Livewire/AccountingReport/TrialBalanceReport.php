<?php

namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Trial Balance Report')]
class TrialBalanceReport extends Component
{

    public float $TOTAL_DEBIT = 0;
    public float $TOTAL_CREDIT = 0;
    public $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    public $accountList = [];
    public array $selectedAccount = [];
    public $accountTypeList = [];
    public array $selectedAccountType = [];
    public $dataList = [];
    private $accountJournalServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $accountServices;
    public function boot(
        AccountJournalServices $accountJournalServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        AccountServices $accountServices
    ) {
        $this->accountJournalServices = $accountJournalServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->accountServices = $accountServices;
    }
    public function mount()
    {
        $this->TOTAL_DEBIT = 0;
        $this->TOTAL_CREDIT = 0;
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID =  $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getAccount(false);
        $this->accountTypeList = $this->accountServices->GetTypeList();
    }

    public function generate()
    {

        $this->dataList = $this->accountJournalServices->getTrialBalance(
            $this->DATE,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );
    }
    public function render()
    {
        return view('livewire.accounting-report.trial-balance-report');
    }
}
