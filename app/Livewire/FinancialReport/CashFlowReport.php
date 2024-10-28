<?php

namespace App\Livewire\FinancialReport;

use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cash Flow Report')]
class CashFlowReport extends Component
{
    public string $DATE_FROM;
    public string $DATE_TO;
    public  $modify = false;
    public int $LOCATION_ID;
    public $locationList = [];
    private $financialStatementServices;
    private $locationServices;
    private $userServices;
    private $dateServices;

    public function boot(
        FinancialStatementServices $financialStatementServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->DATE_TO = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Year($this->DATE_TO);
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate() {}
    public function clickHeader()
    {

        $this->dispatch('open-cf-header', result: ['ID' => 0, 'LOCATION_ID' => $this->LOCATION_ID]);
    }
    public function render()
    {
        return view('livewire.financial-report.cash-flow-report');
    }
}
