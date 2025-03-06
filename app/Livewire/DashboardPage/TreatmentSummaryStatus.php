<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use Livewire\Component;

class TreatmentSummaryStatus extends Component
{
    private $dateServices;
    public $monthlyList = [];
    public $yearList = [];
    public int $month = 0;
    public int $year = 0;
    public int $pre_month = 0;
    public int $pre_year = 0;

    public function boot(DateServices $dateServices)
    {
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->monthlyList = $this->dateServices->FullMonthList();
        $this->yearList = $this->dateServices->YearList();

        $this->month = $this->dateServices->NowMonth();
        $this->year = $this->dateServices->NowYear();
        $this->setPrev();
    }

    private function setPrev()
    {
        $current = $this->month - 1;
        if ($current == 0) {
            $this->pre_year = $this->year - 1;
            $this->pre_month = 12;
        }
        $this->pre_month = $current;


    }
    public function render()
    {
        return view('livewire.dashboard-page.treatment-summary-status');
    }
}
