<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintContent extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $DATE;
    public $PHIC_NO;
    public string $FULL_NAME;
    public string $CODE;
    public string $DOB;
    public int $AGE = 0;
    private $hemoServices;
    private $contactServices;
    public string $OLD_PRE_WEIGHT;
    public string $OLD_PRE_BLOOD_PRESSURE;
    public string $OLD_PRE_BLOOD_PRESSURE2;
    public string $OLD_PRE_HEART_RATE;
    public string $OLD_PRE_O2_SATURATION;
    public string $OLD_PRE_TEMPERATURE;
    public string $OLD_POST_WEIGHT;
    public string $OLD_POST_BLOOD_PRESSURE;
    public string $OLD_POST_BLOOD_PRESSURE2;
    public string $OLD_POST_HEART_RATE;
    public string $OLD_POST_O2_SATURATION;
    public string $OLD_POST_TEMPERATURE;
    public int $CUSTOMER_ID;
    public int $LOCATION_ID;
    public array $collection = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    public string $NEPRHO_NAME;
    public string $DIAGNOSIS = " ";
    public int $NO_OF_TREATMENT = 0;

    public string $SE_DETAILS;
    public string $SO_DETAILS;
    public int $BFR;
    public int $DFR;
    public int $DURATION;
    public string $DIALYZER;
    public string $HEPARIN;
    public string $DIALSATE_N;
    public string $DIALSATE_K;
    public string $DIALSATE_C;
    public $SE_PARTS = [];
    public $SO_PARTS = [];
    public int $SE_COUNT = 0;
    public int $SO_COUNT = 0;

    public string $REPORT_HEADER_1;

    private $patientDoctorServices;
    private $locationServices;
    public function boot(HemoServices $hemoServices, ContactServices $contactServices, PatientDoctorServices $patientDoctorServices, LocationServices $locationServices)
    {
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
        $this->patientDoctorServices = $patientDoctorServices;
        $this->locationServices = $locationServices;
    }
    public function getPreviousTreatment()
    {
        $data = $this->hemoServices->ShowLastTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);

        if ($data) {
            $this->OLD_PRE_WEIGHT = $data->PRE_WEIGHT ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE = $data->PRE_BLOOD_PRESSURE ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE2 = $data->PRE_BLOOD_PRESSURE2 ?? "";
            $this->OLD_PRE_HEART_RATE = $data->PRE_HEART_RATE ?? "";
            $this->OLD_PRE_O2_SATURATION = $data->PRE_O2_SATURATION ?? "";
            $this->OLD_PRE_TEMPERATURE = $data->PRE_TEMPERATURE ?? "";
            $this->OLD_POST_WEIGHT = $data->POST_WEIGHT ?? "";
            $this->OLD_POST_BLOOD_PRESSURE = $data->POST_BLOOD_PRESSURE ?? "";
            $this->OLD_POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
            $this->OLD_POST_HEART_RATE = $data->POST_HEART_RATE ?? "";
            $this->OLD_POST_O2_SATURATION = $data->POST_O2_SATURATION ?? "";
            $this->OLD_POST_TEMPERATURE = $data->POST_TEMPERATURE ?? "";
            return;
        }

        $this->OLD_PRE_WEIGHT = "";
        $this->OLD_PRE_BLOOD_PRESSURE = "";
        $this->OLD_PRE_BLOOD_PRESSURE2 = "";
        $this->OLD_PRE_HEART_RATE = "";
        $this->OLD_PRE_O2_SATURATION = "";
        $this->OLD_PRE_TEMPERATURE = "";
        $this->OLD_POST_WEIGHT = "";
        $this->OLD_POST_BLOOD_PRESSURE = "";
        $this->OLD_POST_BLOOD_PRESSURE2 = "";
        $this->OLD_POST_HEART_RATE = "";
        $this->OLD_POST_O2_SATURATION = "";
        $this->OLD_POST_TEMPERATURE = "";
    }
    public function mount()
    {
        $data = $this->hemoServices->GetFirst($this->HEMO_ID);

        if ($data) {
            $this->FULL_NAME = $data->CONTACT_NAME;
            $this->DATE = $data->DATE;
            $this->CODE = $data->CODE;
            $this->PHIC_NO = $data->PHIC_NO;
            $this->DOB = $data->DATE_OF_BIRTH;
            $this->AGE = $this->contactServices->calculateUserAge($this->DOB);
            $this->CUSTOMER_ID = $data->CUSTOMER_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->SE_DETAILS = $data->SE_DETAILS ?? '';
            $this->SO_DETAILS =  $data->SO_DETAILS ?? '';
            $this->BFR = $data->BFR ?? '';
            $this->DFR = $data->DFR ?? '';
            $this->DURATION = $data->DURATION ?? 0;
            $this->DIALYZER = $data->DIALYZER ?? '';
            $this->HEPARIN = $data->HEPARIN ?? '';
            $this->DIALSATE_N =  $data->DIALSATE_N ?? '';
            $this->DIALSATE_K = $data->DIALSATE_K ?? '';
            $this->DIALSATE_C = $data->DIALSATE_C ?? '';
            $this->SE_COUNT = 0;
            $this->SE_PARTS = str_split($this->SE_DETAILS, 35);

            $this->SO_COUNT = 0;
            $this->SO_PARTS = str_split($this->SO_DETAILS, 35);
            $this->getPreviousTreatment();
            $this->NO_OF_TREATMENT = $this->hemoServices->GetNoTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);
            $dataDoc = $this->patientDoctorServices->GetList($this->CUSTOMER_ID);
            foreach ($dataDoc as $doc) {
                $this->NEPRHO_NAME = $doc->NAME ?? '';
            }

            $dataPatient = $this->contactServices->get($this->CUSTOMER_ID, 3);
            if ($dataPatient) {
                $this->DIAGNOSIS = $dataPatient->FINAL_DIAGNOSIS ?? '';
            }

           $locData =  $this->locationServices->get($this->LOCATION_ID);
           if($locData) {
            $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
           }
        }
    }

    public function render()
    {
        return view('livewire.hemodialysis.print-content');
    }
}
