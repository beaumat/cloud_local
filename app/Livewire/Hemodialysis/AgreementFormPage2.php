<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\LocationServices;
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

    private $hemoServices;
    private $locationServices;
    public function boot(HemoServices $hemoServices, LocationServices $locationServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
    }

    public function mount()
    {

        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->DATE = $data->DATE;
            $this->LOCATION_ID = $data->LOCATION_ID;
         
            $dataLoc = $this->locationServices->get($this->LOCATION_ID);
            if($dataLoc) {

                // $this->ADMIN_NAME ="";

            }
        }

    }
    public function render()
    {
        return view('livewire.hemodialysis.agreement-form-page2');
    }
}
