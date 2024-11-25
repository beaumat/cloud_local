<?php

namespace App\Livewire\DoctorFee;

use App\Services\DoctorPFServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Component;

class DoctorFeeList extends Component
{


    public int $LOCATION_ID;
    public $locationList = [];
    public $doctorList = [];
    private $doctorPFServices;
    private $locationServices;
    private $userServices;


    public function boot(DoctorPFServices $doctorPFServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->doctorPFServices = $doctorPFServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function Generate()
    {
        $data = $this->doctorPFServices->getDoctorList($this->LOCATION_ID);

        $this->doctorList = $data;
    }
    public function updatedlocationid()
    {
        $this->doctorList  = [];
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function openList(int $DOCTOR_ID)
    {
        $data = [
            'DOCTOR_ID' => $DOCTOR_ID,
            'LOCATION_ID' => $this->LOCATION_ID
        ];
        $this->dispatch('pf-open-list', result: $data);
    }
    public function printList() {}
    public function render()
    {
        return view('livewire.doctor-fee.doctor-fee-list');
    }
}
