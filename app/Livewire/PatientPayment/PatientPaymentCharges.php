<?php

namespace App\Livewire\PatientPayment;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PatientPaymentCharges extends Component
{
    #[Reactive]
    public int $PATIENT_PAYMENT_ID;
    public $dataList = [];
    public int $STATUS;
    public int $openStatus;
    private $patientPaymentServices;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public $editPaymentId = null;
    public int $editInvoiceId;
    public float $editAmountApplied;
    private $serviceChargeServices;
    public function boot(PatientPaymentServices $patientPaymentServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function edit(int $ID, int $SERVICE_CHARGES_ID, float $Applied)
    {
        $this->editPaymentId = $ID;
        $this->editInvoiceId = $SERVICE_CHARGES_ID;
        $this->editAmountApplied = $Applied;
    }

    public function cancel()
    {
        $this->editPaymentId = null;

    }
    public function update()
    {
        $this->patientPaymentServices->PaymentChargesUpdate($this->editPaymentId, $this->PATIENT_PAYMENT_ID, $this->editInvoiceId, 0, $this->editAmountApplied);
        $this->serviceChargeServices->updateServiceChargesBalance($this->editInvoiceId);
        $this->editPaymentId = null;
        $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $SERVICE_CHARGES_ID)
    {
        $this->patientPaymentServices->PaymentChargesDelete($ID, $this->PATIENT_PAYMENT_ID, $SERVICE_CHARGES_ID);
        $this->serviceChargeServices->updateServiceChargesBalance($SERVICE_CHARGES_ID);
        $this->dispatch('reset-payment');
    }
    public function mount(int $PATIENT_PAYMENT_ID, int $PATIENT_ID, int $LOCATION_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->PATIENT_PAYMENT_ID = $PATIENT_PAYMENT_ID;
        $this->PATIENT_ID = $PATIENT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;

    }
    #[On('reload_payment_invoice')]
    public function render()
    {

        $this->dataList = $this->patientPaymentServices->PaymentChargesList($this->PATIENT_PAYMENT_ID);
        return view('livewire.patient-payment.patient-payment-charges');
    }
}
