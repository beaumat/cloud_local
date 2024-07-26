<?php

namespace App\Livewire\Doctor;

use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\UserServices;
use Livewire\Component;

class DoctorProfessionalFeeModal extends Component
{


    public int $LOCATION_ID;
    public $locationList = [];
    public bool $showModal = false;
    private $locationServices;
    private $userServices;
    private $patientDoctorServices;
    public function boot(LocationServices $locationServices, UserServices $userServices, PatientDoctorServices $patientDoctorServices)
    {
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->patientDoctorServices = $patientDoctorServices;
    }
    
    public function openModal()
    {
        $this->showModal = true;
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function Generate() {

    }
    public function render()
    {


        return view('livewire.doctor.doctor-professional-fee-modal');
    }
}
