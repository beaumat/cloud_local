<?php

namespace App\Livewire\PatientReport;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Patient Inventory Report')]
class PatientInventoryReport extends Component
{

    public bool $refreshComponent = false;
    public  $DATE_FROM;
    public $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList = [];
    private $patientReportServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    public function boot(PatientReportServices $patientReportServices, DateServices $dateServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->patientReportServices = $patientReportServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->LOCATION_ID =  $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate() {
        
        $this->dataList = $this->patientReportServices->getInventoryList($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
    }
    public function render()
    {
        return view('livewire.patient-report.patient-inventory-report');
    }
}
