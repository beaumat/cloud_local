<?php

namespace App\Livewire\ReceivableReport;

use App\Services\AgingServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Accounts Receivable Aging Reports")]
class AccountReceivableAging extends Component
{
    public bool $D_CURRENT = false;
    public bool $D_1_30 = false;
    public bool $D_31_60 = false;
    public bool $D_61_90 = false;
    public bool $D_91_OVER = false;

    public bool $isSummary = true;
    public string $DATE;
    public int $LOCATION_ID;
    public $locationList = [];
    public $summaryList = [];
    public $detailList = [];
    private $locationServices;
    private $userServices;
    private $agingServices;
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        AgingServices $agingServices
    ) {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->agingServices = $agingServices;
    }
    public function mount()
    {
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function summary()
    {
        $this->isSummary = true;
        $this->summaryList = $this->agingServices->ARAgingSummary($this->DATE, $this->LOCATION_ID, []);
    }
    public function details()
    {   
          $this->D_CURRENT = false;
          $this->D_1_30 = false;
          $this->D_31_60 = false;
          $this->D_61_90 = false;
          $this->D_91_OVER = false;


        $this->isSummary = false;
        $this->detailList =  $this->agingServices->ARAgingDetais($this->DATE, $this->LOCATION_ID, []);
    }
    public function render()
    {
        return view('livewire.receivable-report.account-receivable-aging');
    }
}
