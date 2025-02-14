<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormPage2 extends Component
{
    #[Reactive]
    public int $HEMO_ID;


    public $ADMIN_NAME = "UNKNOWN";
    public $PATIENT_NAME = "UNKNOWN";
    public $WITNESS_NAME = "UNKNOWN";

    public string $DATE;

    public int $LOCATION_ID;
    public $typeFiveList = [];
    public $typeSixList = [];
    private $hemoServices;
    private $locationServices;
    private $phicAgreementFormServices;
    public function boot(HemoServices $hemoServices, LocationServices $locationServices, PhicAgreementFormServices $phicAgreementFormServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->phicAgreementFormServices = $phicAgreementFormServices;
    }

    public function mount()
    {

        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->DATE = $data->DATE;
            $this->LOCATION_ID = $data->LOCATION_ID;

            $dataLoc = $this->locationServices->get($this->LOCATION_ID);
            if ($dataLoc) {

                // $this->ADMIN_NAME ="";

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
