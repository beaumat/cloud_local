<?php

namespace App\Livewire\Patient;

use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class PhilhealthModify extends Component
{   
    #[Reactive]
    public int $PATIENT_ID;
    
    public bool $showModal = false;
    
    private $serviceChargeServices;
    public function boot(ServiceChargeServices $serviceChargeServices) {
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function Add() {

    }
    public function Delete() {

    }
    #[On('open-philhealth-modifiy')]
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.patient.philhealth-modify');
    }
}
