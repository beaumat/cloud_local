<?php

namespace App\Livewire\AccountingReport;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('General Ledger Report')]
class GeneralLedgerReport extends Component
{

    public string $TEMP_ACCOUNT = "";
    public float $TEMP_DEBIT = 0;
    public float $TEMP_CREDIT = 0;


    public float $TOTAL_DEBIT = 0;
    public float $TOTAL_CREDIT = 0;
    public float $BALANCE  = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public $accountList  = [];
    public $accountTypeList = [];
    public array $selectedAccount = [];
    public array $selectedAccountType = [];
    public $dataList =  [];
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
        AccountServices $accountServices,

    ) {
        $this->accountJournalServices = $accountJournalServices;
        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
        $this->userServices = $userServices;
        $this->accountServices = $accountServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->LOCATION_ID =  $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getAccount(false);
        $this->accountTypeList = $this->accountServices->GetTypeList();
    }
    public function generate()
    {

        $this->dataList = $this->accountJournalServices->getGeneralLedgerList(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->selectedAccount,
            $this->selectedAccountType
        );
    }

    public function render()
    {
        return view('livewire.accounting-report.general-ledger-report');
    }
}
