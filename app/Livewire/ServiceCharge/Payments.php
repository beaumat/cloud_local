<?php

namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Payments extends Component
{

    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public int $openStatus = 0;
    public $data = [];
    private $patientPaymentServices;
    private $serviceChargeServices;
    public function boot(PatientPaymentServices $patientPaymentServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function delete(int $ID, int $PATIENT_PAYMENT_ID)
    {
        $this->patientPaymentServices->PaymentChargesDelete($ID, $PATIENT_PAYMENT_ID, $this->SERVICE_CHARGES_ID);
        $this->patientPaymentServices->UpdatePaymentChargesApplied($PATIENT_PAYMENT_ID);
        $this->serviceChargeServices->updateServiceChargesBalance($this->SERVICE_CHARGES_ID);
        $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
    }
    public function render()
    {
        $this->data = $this->patientPaymentServices->ServiceChargesPaymentList($this->SERVICE_CHARGES_ID, $this->PATIENT_ID);
        return view('livewire.service-charge.payments');
    }

}
