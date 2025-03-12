<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Guarantee Letter Report')]
class GuaranteeLetterReport extends Component
{   
   
    public  bool $refreshComponent = false;
    public int $PATIENT_ID = 0;
    public int $LOCATION_ID;
    public string $DATE_FROM;
    public string $DATE_TO;
    public float $BALANCE;
    public  $locationList = [];
    public $patientList = [];
    public $dataList = [];

    private $patientPaymentServices;
    private $dateServices;
    private $userServices;
    private $locationServices;
    private $contactServices;
    public function boot(
        ContactServices $contactServices,
        PatientPaymentServices $patientPaymentServices,
        DateServices $dateServices,
        UserServices $userServices,
        LocationServices $locationServices
    ) {
    
        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
        $this->userServices = $userServices;
        $this->contactServices = $contactServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function export()
    {
        // return Excel::download(new SalesPatientBalanceExport(
        //     $this->serviceChargeServices,
        //     $this->PATIENT_ID,
        //     $this->LOCATION_ID,
        //     $this->DATE_FROM,
        //     $this->DATE_TO

        // ), 'patient-balance.xlsx');
    }
    public function mount()
    {
        $this->locationList  = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->updatedlocationId();
    }
    public function updatedlocationId()
    {

        $this->patientList = $this->contactServices->getPatientList($this->LOCATION_ID);
        $this->PATIENT_ID  = 0;
        $this->refreshComponent = $this->refreshComponent ? false : true;
    }
    public function generate()
    {
        $this->BALANCE = 0;
        $this->dataList =  $this->patientPaymentServices->GetAssistanceAll($this->PATIENT_ID,$this->LOCATION_ID);
 
    }
    public function render()
    {
        return view('livewire.patient-report.guarantee-letter-report');
    }
}
