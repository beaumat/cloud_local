<?php

namespace App\Livewire\Location;

use App\Services\ContactServices;
use App\Services\DoctorLocationServices;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Doctor Location')]
class LocationDoctors extends Component
{

    public $dataList = [];
    public int $LOCATION_ID;

    private $doctorLocationServices;
    private $contactServices;
    public function boot(DoctorLocationServices $doctorLocationServices, ContactServices $contactServices)
    {
        $this->doctorLocationServices = $doctorLocationServices;
        $this->contactServices = $contactServices;
    }
    public function mount(int $id)
    {
        $this->LOCATION_ID = $id;
    }

    public int $DOCTOR_ID;
    public function Add()
    {
        $this->doctorLocationServices->Store($this->LOCATION_ID, $this->DOCTOR_ID);
    }
    public function Delete(int $ID)
    {
        $this->doctorLocationServices->Delete($this->LOCATION_ID, $ID);
    }

    public function render()
    {

        $this->dataList = $this->doctorLocationServices->ViewList($this->LOCATION_ID);

        return view('livewire.location.location-doctors');
    }
}
