<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Sales Report')]
class PatientSalesReport extends Component
{   

    public $PATIENT_ID;
    public $LOCATION_ID;
    
    public $patientList= [];
    public string $DATE_FROM;
    public string $DATE_TO;
    private $contactServices;
    private $dateServices;
    public function boot(ContactServices $contactServices, DateServices $dateServices)
     {
        $this->contactServices = $contactServices;
        $this->dateServices =   $dateServices;
     }
    public function mount() {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->patientList = $this->contactServices->getList(3);
    }
    public function render()
    {       


        return view('livewire.patient-report.patient-sales-report');
    }
}
