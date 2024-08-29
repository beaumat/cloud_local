<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\PatientDoctorServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorPanel extends Component
{

    #[Reactive]
    public int $ID;
    public int $DOCTOR_ID = 0;
    public bool $saveSuccess = false;
    public $dataList = [];
    public $contactList = [];

    private $patientDoctorServices;
    private $contactServices;

    public function boot(
        PatientDoctorServices $patientDoctorServices,
        ContactServices $contactServices
    ) {
        $this->patientDoctorServices = $patientDoctorServices;
        $this->contactServices = $contactServices;
    }
    public function mount()
    {
        $data = $this->contactServices->get($this->ID, 3);
        if ($data) {

            $this->contactList = $this->contactServices->getDoctorListByLocation($data->LOCATION_ID ?? 0);
        }
    }
    public function delete(int $id)
    {
        try {
            $this->patientDoctorServices->delete($id);
            session()->flash('message', 'Successfully deleted');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function save()
    {
        if ($this->ID == 0) {

            return;
        }


        if ($this->DOCTOR_ID == 0) {
            session()->flash('error', 'Please select.');
            return;
        }


        if ($this->patientDoctorServices->AlreadyExists($this->ID)) {
            session()->flash('error', 'Nephro already added.');
            return;
        }
        try {





            $this->patientDoctorServices->Store($this->ID, $this->DOCTOR_ID);
            $this->DOCTOR_ID = 0;
            $this->saveSuccess = $this->saveSuccess ? false : true;
            session()->flash('message', 'Successfully added');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function render()
    {

        $this->dataList = $this->patientDoctorServices->GetList($this->ID > 0 ? $this->ID : 0);
        return view('livewire.patient.doctor-panel');
    }
}
