<?php

namespace App\Livewire\DashboardPage;

use App\Services\DateServices;
use App\Services\PatientStatusServices;
use Livewire\Component;

class TreatmentSummaryStatus extends Component
{   
    public $locaitonList = [];
    private $dateServices;
    public $monthlyList = [];
    public $yearList = [];
    public int $month = 0;
    public int $year = 0;
    public int $pre_month = 0;
    public int $pre_year = 0;
    private $patientStatusServices;
    public function boot(PatientStatusServices $patientStatusServices,DateServices $dateServices)
    {
        $this->dateServices = $dateServices;
        $this->patientStatusServices = $patientStatusServices;
    }
    public function mount()
    {
        $this->monthlyList = $this->dateServices->FullMonthList();
        $this->yearList = $this->dateServices->YearList();

        $this->month = $this->dateServices->NowMonth();
        $this->year = $this->dateServices->NowYear();
    
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
        $this->setPrev();
        $this->locaitonList = $this->patientStatusServices->getList($this->month, $this->year);
        return view('livewire.dashboard-page.treatment-summary-status');
    }
}
