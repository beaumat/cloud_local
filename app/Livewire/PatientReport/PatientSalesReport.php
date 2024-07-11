<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sales Report')]
class PatientSalesReport extends Component
{

    public int $PATIENT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public $patientList = [];
    public string $DATE_FROM;
    public string $DATE_TO;
    private $contactServices;
    private $dateServices;

    private $locationServices;

    private $userServices;
    public function boot(ContactServices $contactServices, DateServices $dateServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->contactServices = $contactServices;
        $this->dateServices =   $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->patientList = $this->contactServices->getList(3);

        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }
    public function render()
    {


        return view('livewire.patient-report.patient-sales-report');
    }
}
