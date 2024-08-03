<?php

namespace App\Livewire\PatientReport;

use App\Services\DoctorPFServices;
use Livewire\Component;

class DoctorsFeeReportPrint extends Component
{   

    private $doctorPFServices;
    public function boot(DoctorPFServices $doctorPFServices)
    {
            $this->doctorPFServices = $doctorPFServices;
    }
    public function render()
    {
        return view('livewire.patient-report.doctors-fee-report-print');
    }
}
