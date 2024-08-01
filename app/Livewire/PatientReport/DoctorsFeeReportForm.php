<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DoctorPFServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorsFeeReportForm extends Component
{

    public bool $isDisabled = false;
    public bool $showModal = false;
    public $dataList = [];
    public string $DOCTOR_NAME;
    private $doctorPFServices;
    private $contactServices;
    public function boot(DoctorPFServices $doctorPFServices, ContactServices $contactServices)
    {
        $this->doctorPFServices = $doctorPFServices;
        $this->contactServices = $contactServices;
    }
    #[On('pf-open-list')]
    public function openModal($result)
    {
        $contactData = $this->contactServices->get($result['DOCTOR_ID'], 4);

        $this->DOCTOR_NAME =  $contactData->PRINT_NAME_AS;
        $this->dataList = $this->doctorPFServices->PatientGenerate($result['LOCATION_ID'], $result['DOCTOR_ID']);
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.patient-report.doctors-fee-report-form');
    }
}
