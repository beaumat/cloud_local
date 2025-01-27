<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\MedCertServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;




class MedcertModal extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    public bool $showModal = false;
    public int $MED_CERT_SCHED_ID;
    public int $MED_CERT_NURSE_ID;
    public $medCertScheduleList =  [];
    public $contactList = [];


    private $contactServices;
    private $medCertServices;
    public function boot(ContactServices $contactServices, MedCertServices $medCertServices)
    {
        $this->contactServices = $contactServices;
        $this->medCertServices = $medCertServices;
    }

    #[On('open-med-cert')]
    public function openModal()
    {
        $data = $this->contactServices->getPatientByMed($this->PATIENT_ID);
        if ($data) {

            $this->MED_CERT_SCHED_ID = $data->MED_CERT_SCHED_ID ?? 0;
            $this->MED_CERT_NURSE_ID = $data->MED_CERT_NURSE_ID ?? 0;
            $this->contactList = $this->contactServices->getList(2);
            $this->medCertScheduleList = $this->medCertServices->GetList();
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function SaveChange()
    {

        $this->validate(
            [
                'MED_CERT_SCHED_ID' => 'required|exists:medcert_sched,id',
                'MED_CERT_NURSE_ID' => 'required|exists:contact,id',

            ],
            [],
            [
                'MED_CERT_SCHED_ID' => 'Schedule Description',
                'MED_CERT_NURSE_ID' => 'Duty Physician'
            ]
        );

        try {
            $this->medCertServices->UpdatePatientMedCert($this->PATIENT_ID, $this->MED_CERT_SCHED_ID, $this->MED_CERT_NURSE_ID);
            session()->flash('message', 'Successfully updated');
      
        } catch (\Exception $th) {
            session()->flash('error', $th->getMessage());
      
        }
    }


    public function render()
    {

        return view('livewire.patient.medcert-modal');
    }
}
