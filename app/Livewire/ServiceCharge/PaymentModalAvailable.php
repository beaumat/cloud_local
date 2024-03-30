<?php

namespace App\Livewire\ServiceCharge;

use App\Services\InvoiceServices;
use App\Services\PaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentModalAvailable extends Component
{

    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $INVOICE_ID;
    private $paymentServices;
    private $invoiceServices;
    public $data = [];
    public $paymentSelected = [];
    public $paymentAmounts = [];
    public $paymentRemain = [];
    public float $balance;
    public function boot(PaymentServices $paymentServices, InvoiceServices $invoiceServices)
    {
        $this->paymentServices = $paymentServices;
        $this->invoiceServices = $invoiceServices;
    }
    public function mount()
    {
        $this->balance = $this->invoiceServices->getBalance($this->INVOICE_ID);
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
                    $CurrentRemain = $this->paymentServices->GetPaymentRemaining($paymentId);

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

        // $newPay = $CurrentAmount - $CollectAmount;
        // if ($this->balance <= $newPay) {
        //     $mustPay = $this->balance;
        // } else {
        //     $mustPay = $newPay;
        // }

        // $this->paymentAmounts[$id] = $mustPay;
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
                $isNoSelected = true;
                return;
            }

            $CurrentAmount = $this->paymentServices->GetPaymentRemaining($paymentId);

            if ($CollectAmount > $CurrentAmount) {
                session()->flash('error', 'Invalid amount');
                $isNoSelected = true;
                return;
            }

            $isNoSelected = false;
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

                if ($appliedAmount) {
                    $ID = (int) $this->paymentServices->PaymentInvoiceExist($paymentId, $this->INVOICE_ID);
                    if ($ID > 0) {
                        $this->paymentServices->PaymentInvoiceUpdate($ID, $paymentId, $this->INVOICE_ID, 0, $appliedAmount);
                    } else {
                        $this->paymentServices->PaymentInvoiceStore($paymentId, $this->INVOICE_ID, 0, $appliedAmount, 0, 0);
                    }
                    $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);
                    $this->paymentServices->UpdatePaymentApplied($paymentId);

                }

            }
        }

        $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
        $this->paymentSelected = [];
        $this->paymentAmounts = [];

        $this->dispatch('payment-modal-close');

    }
    public function render()
    {

        $this->data = $this->paymentServices->PaymentAvailableList($this->CUSTOMER_ID, $this->LOCATION_ID);
        return view('livewire.service-charge.payment-modal-available');
    }
}
