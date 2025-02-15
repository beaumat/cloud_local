<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormPage2 extends Component
{
    #[Reactive]
    public int $HEMO_ID;


    public $HD_FACILITY_REP_NAME = "UNKNOWN";
    public $PATIENT_NAME = "UNKNOWN";
    public $WITNESS_NAME = "UNKNOWN";

    public string $DATE;

    public int $LOCATION_ID;

    public $typeFiveList = [];
    public $typeSixList = [];
    private $hemoServices;
    private $locationServices;
    private $phicAgreementFormServices;
    private $contactServices;

    public function boot(HemoServices $hemoServices, LocationServices $locationServices, PhicAgreementFormServices $phicAgreementFormServices, ContactServices $contactServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->phicAgreementFormServices = $phicAgreementFormServices;
        $this->contactServices = $contactServices;
    }

    public function mount()
    {
        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->PATIENT_NAME = $this->contactServices->getName($data->CUSTOMER_ID);
            $this->DATE = $data->DATE;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $dataLoc = $this->locationServices->get($this->LOCATION_ID);

            if ($dataLoc) {
                $this->HD_FACILITY_REP_NAME = $this->contactServices->getName($dataLoc->HD_FACILITY_REP_ID);


            }

            $this->TypeFive();
            $this->TypeSix();
        }

    }

    private function TypeFive()
    {
        $this->typeFiveList = $this->phicAgreementFormServices->getTitleByType(5);
    }
    private function TypeSix()
    {
        $this->typeSixList = $this->phicAgreementFormServices->getTitleByType(6);
    }
    public function render()
    {
        return view('livewire.hemodialysis.agreement-form-page2');
    }
}
