<?php

namespace App\Livewire\PatientPayment;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ServiceChargeList extends Component
{
    public $showModal = false;
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $PATIENT_PAYMENT_ID;
    #[Reactive]
    public float $AMOUNT;
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public $dataList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $serviceChargeServices;
    private $patientPaymentServices;
    public bool $isDisabled = false;
    public function boot(ServiceChargeServices $serviceChargeServices, PatientPaymentServices $patientPaymentServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function mount(int $PATIENT_ID, int $LOCATION_ID, int $PATIENT_PAYMENT_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->PATIENT_ID = $PATIENT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->PATIENT_PAYMENT_ID = $PATIENT_PAYMENT_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    public function updatedSelectedCharges(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }

        $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
        $CollectAmount = 0;
        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }
        $newPay = $CurrentAmount - $CollectAmount;
        $balance = $this->serviceChargeServices->getBalance($id);
        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
        }
        $this->paymentAmounts[$id] = $mustPay;
    }
    public function openModal()
    {
        $this->isDisabled = $this->CheckingHavePaymentNotUsed();
        $this->showModal = true;
    }
    public function closeModal()
    {

        $this->showModal = false;
    }
    private function CheckingHavePaymentNotUsed(): bool
    {
        $data = $this->patientPaymentServices->PaymentHaveAvailable($this->PATIENT_ID);
        if ($data) {
            if ($data->ID != $this->PATIENT_PAYMENT_ID) {
                session()->flash('error', "Some payment have not applied. please check this url: <a target='_BLANK' href ='" . route('patientspayment_edit', ['id' => $data->ID]) . "'> Here </a> ");
                return true;
            }
        }
        return false;
    }
    public function save()
    {
        $CurrentAmount = (float) $this->AMOUNT - $this->AMOUNT_APPLIED;
        $CollectAmount = 0;
        //Check Amount First
        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $CollectAmount = $CollectAmount + $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = $CollectAmount + 0;
                }
            }
        }

        if ($CollectAmount == 0) {
            session()->flash('error', 'payment selected not found.');
            return;
        }

        if ($CollectAmount > $CurrentAmount) {
            session()->flash('error', 'Invalid amount');
            return;
        }

        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $chargeAmount = $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $chargeAmount = 0;
                }
                if ($chargeAmount) {
                    $ID = (int) $this->patientPaymentServices->PaymentChargesExist($this->PATIENT_PAYMENT_ID, $chargeId);
                    if ($ID > 0) {
                        $this->patientPaymentServices->PaymentChargesUpdate($ID, $this->PATIENT_PAYMENT_ID, $chargeId, 0, $chargeAmount);
                        $this->serviceChargeServices->updateServiceChargesBalance($chargeId);

                    } else {
                        $this->patientPaymentServices->PaymentChargeStore($this->PATIENT_PAYMENT_ID, $chargeId, 0, $chargeAmount, 0, 0);
                        $this->serviceChargeServices->updateServiceChargesBalance($chargeId);
                    }
                    $this->dispatch('reset-payment');
                }

            }
        }

        $this->showModal = false;
        $this->selectedCharges = [];
        $this->paymentAmounts = [];
        $this->dispatch('reload_payment_invoice');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {

        $this->dataList = $this->serviceChargeServices->getServiceChargeList($this->PATIENT_ID, $this->LOCATION_ID, $this->PATIENT_PAYMENT_ID);

        return view('livewire.patient-payment.service-charge-list');
    }
}
