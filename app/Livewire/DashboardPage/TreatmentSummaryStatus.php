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
    public int $prev_month = 0;
    public int $prev_year = 0;
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
            $this->prev_year = $this->year - 1;
            $this->prev_month = 12;
            return;
        }
        $this->prev_month = $current;
        $this->prev_year = $this->year;


    }
    public function render()
    {
        $this->setPrev();
        // dd($this->prev_month . '   ' . $this->prev_year );
        $this->locaitonList = $this->patientStatusServices->getTreatmentSummaryList($this->month, $this->year, $this->prev_month, $this->prev_year);
        return view('livewire.dashboard-page.treatment-summary-status');
    }
}
