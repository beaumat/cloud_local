<?php

namespace App\Livewire\ServiceCharge;

use App\Services\PatientPaymentServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentModalAvailable extends Component
{

    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    private $patientPaymentServices;
    private $serviceChargeServices;
    public $data = [];
    public $paymentSelected = [];
    public $paymentAmounts = [];
    public $paymentRemain = [];
    public float $balance;
    public function boot(PatientPaymentServices $patientPaymentServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function mount()
    {
        $this->balance = $this->serviceChargeServices->getBalance($this->SERVICE_CHARGES_ID);
    }
    public function updatedpaymentSelected(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }
        $myBalance = $this->balance;
        foreach ($this->paymentSelected as $paymentId => $isSelected) {

            if ($isSelected) {
                try {
                    $CurrentRemain = $this->patientPaymentServices->GetPaymentRemaining($paymentId);
                    if ($CurrentRemain == $myBalance) {
                        $this->paymentAmounts[$paymentId] = $CurrentRemain;
                    } else {
                        if ($myBalance <= $CurrentRemain) {
                            $this->paymentAmounts[$paymentId] = $myBalance;
                        } else {
                            $this->paymentAmounts[$paymentId] = $CurrentRemain;
                        }
                    }

                    $myBalance = $myBalance - $this->paymentAmounts[$paymentId];

                } catch (\Throwable $th) {
                    // $CollectAmount = $CollectAmount + 0;
                    // $CurrentAmount = $CurrentAmount + 0;
                }
            }
        }
    }
    public function save()
    {
        $CollectAmount = 0;
        $isNoSelected = true;
        //Check Amount First
        foreach ($this->paymentSelected as $paymentId => $isSelected) {

            if ($isSelected) {
                try {
                    $CollectAmount = $this->paymentAmounts[$paymentId] ?? 0;
                } catch (\Throwable $th) {
                    $CollectAmount = 0;
                }
            }
            if ($CollectAmount == 0) {
                session()->flash('error', 'Please enter payment applied.');
                break;
            } else {
                if ($CollectAmount > $this->patientPaymentServices->GetPaymentRemaining($paymentId)) {
                    session()->flash('error', 'Invalid amount');
                    break;
                } else {
                    $isNoSelected = false;
                }
            }
        }


        if ($isNoSelected) {
            session()->flash('error', 'Payment not selected');
            return;
        }
        foreach ($this->paymentSelected as $paymentId => $isSelected) {
            $appliedAmount = 0;
            if ($isSelected) {
                try {
                    $appliedAmount = $this->paymentAmounts[$paymentId] ?? 0;
                } catch (\Throwable $th) {
                    $appliedAmount = 0;
                }

                if ($appliedAmount > 0) {
                    $ID = (int) $this->patientPaymentServices->PaymentChargesExist($paymentId, $this->SERVICE_CHARGES_ID);
                    if ($ID > 0) {
                        $this->patientPaymentServices->PaymentChargesUpdate($ID, $paymentId, $this->SERVICE_CHARGES_ID, 0, $appliedAmount);
                    } else {
                        $this->patientPaymentServices->PaymentChargeStore($paymentId, $this->SERVICE_CHARGES_ID, 0, $appliedAmount, 0, 0);
                    }

                    $this->serviceChargeServices->updateServiceChargesBalance($this->SERVICE_CHARGES_ID);
                    
                    $this->patientPaymentServices->UpdatePaymentChargesApplied($paymentId);

                }

            }
        }

        $getResult = $this->serviceChargeServices->ReComputed($this->SERVICE_CHARGES_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
        $this->paymentSelected = [];
        $this->paymentAmounts = [];
        $this->dispatch('payment-modal-close');

    }
    public function render()
    {

        $this->data = $this->patientPaymentServices->PaymentAvailableList($this->PATIENT_ID, $this->LOCATION_ID);

        return view('livewire.service-charge.payment-modal-available');
    }
}
