<?php

namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Component;

class CashPaymentMulti extends Component
{

    public bool $showModal = false;

    private $serviceChargeServices;
    private $patientPaymentServices;
    private $userServices;

    public function boot(ServiceChargeServices $serviceChargeServices, PatientPaymentServices $patientPaymentServices, UserServices $userServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
        $this->userServices = $userServices;
    }
    #[On('cash-payment-multi')]
    public function openModal($result)
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.service-charge.cash-payment-multi');
    }
}
