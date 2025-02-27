<?php

namespace App\Livewire\FinancialReport;

use App\Exports\BalanceSheetExport;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Balance Sheet Report')]
class BalanceSheetReport extends Component
{
    public bool $isDate = true;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public int $YEAR;
    public $locationList = [];
    public $dataList = [];

    private $locationServices;
    private $userServices;
    private $dateServices;

    public function boot(

        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices

    ) {

        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;

    }
    public function mount()
    {
        $this->YEAR = $this->dateServices->NowYear();
        $this->DATE_TO = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Year($this->DATE_TO);
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generateMonthly()
    {
        $this->isDate = false;
        $this->dispatch('proccess-balance');
    }
    public function generate()
    {
        $this->isDate = true;
        $this->dispatch('proccess-balance');

    }
    #[On('proccess-balance')]
    public function getData()
    {
        if ($this->isDate) {
            $this->dispatch('balance-sheet-date-range', result: ['DATE_FROM' => $this->DATE_FROM, 'DATE_TO' => $this->DATE_TO, 'LOCATION_ID' => $this->LOCATION_ID]);
        } else {
            $this->dispatch('balance-sheet-monthly', result: ['YEAR' => $this->YEAR, 'LOCATION_ID' => $this->LOCATION_ID]);
        }
    }
    public function export()
    {
        if (!$this->dataList) {
            session()->flash('error', 'Please generate first.');
            return;
        }
        return Excel::download(new BalanceSheetExport(
            $this->dataList
        ), 'balance-sheet-export.xlsx');
    }
    public function render()
    {
        return view('livewire.financial-report.balance-sheet-report');
    }
}
