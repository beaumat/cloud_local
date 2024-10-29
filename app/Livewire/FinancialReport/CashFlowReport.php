<?php

namespace App\Livewire\FinancialReport;

use App\Services\CashFlowServices;
use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cash Flow Report')]
class CashFlowReport extends Component
{
    public float $AMOUNT  = 0;
    public int $YEAR;

    public  $modify = false;
    public int $LOCATION_ID;
    public $locationList = [];

    public $headerList = [];
    public $detailList = [];
    private $financialStatementServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $cashFlowServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        CashFlowServices $cashFlowServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->cashFlowServices = $cashFlowServices;
    }
    public function mount()
    {

        $this->YEAR = $this->dateServices->NowYear();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }

    public function generate() {

        
    }
    public function clickHeader()
    {
        $this->dispatch('open-cf-header', result: ['ID' => 0, 'LOCATION_ID' => $this->LOCATION_ID]);
    }
    public function clickDetails(int $CF_HEADER_ID)
    {
        $this->dispatch('open-cf-details', result: ['ID' => 0, 'CF_HEADER_ID' => $CF_HEADER_ID]);
    }
    public function clickKey(int $CS_FLOW_DETAILS_ID)
    {
        $this->dispatch('open-cf-key', result: ['ID' => 0, 'CS_FLOW_DETAILS_ID' => $CS_FLOW_DETAILS_ID]);
    }
    public function editHeader(int $ID)
    {
        $this->dispatch('open-cf-header', result: ['ID' => $ID, 'LOCATION_ID' => $this->LOCATION_ID]);
    }
    public function editDetails(int $ID)
    {
        $this->dispatch('open-cf-details', result: ['ID' => $ID, 'CF_HEADER_ID' => 0]);
    }
    public function editKey(int $ID)
    {
        $this->dispatch('open-cf-key', result: ['ID' => $ID, 'CS_FLOW_DETAILS_ID' => 0]);
    }
    #[On('refresh-generate')]
    public function render()
    {
        $this->AMOUNT  = 0;
        return view('livewire.financial-report.cash-flow-report');
    }
}
