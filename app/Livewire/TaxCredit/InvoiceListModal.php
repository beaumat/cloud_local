<?php

namespace App\Livewire\TaxCredit;

use App\Services\InvoiceServices;
use App\Services\TaxCreditServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceListModal extends Component
{ public $showModal = false;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $TAX_CREDIT_ID;
    #[Reactive]
    public float $TAX_RATE;

    public $invoiceList = [];
    public $selectedCharges = [];
    public $paymentAmounts = [];
    private $invoiceServices;
    private $taxCreditServices;

    public function boot(InvoiceServices $invoiceServices, TaxCreditServices $taxCreditServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->taxCreditServices = $taxCreditServices;
    }
    public function mount(int $CUSTOMER_ID, int $LOCATION_ID, int $TAX_CREDIT_ID)
    {
        $this->CUSTOMER_ID = $CUSTOMER_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->TAX_CREDIT_ID = $TAX_CREDIT_ID;
  

    }
    public function updatedSelectedCharges(bool $value, $id)
    {
        if (!$value) {
            $this->paymentAmounts[$id] = 0;
            return;
        }

        $CurrentAmount = (float) $this->AMOUNT;
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
        $balance = $this->invoiceServices->getBalance($id);
        if ($balance <= $newPay) {
            $mustPay = $balance;
        } else {
            $mustPay = $newPay;
        }
        $this->paymentAmounts[$id] = $mustPay;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function save()
    {
     
      

        foreach ($this->selectedCharges as $chargeId => $isSelected) {
            if ($isSelected) {
                try {
                    $chargeAmount = $this->paymentAmounts[$chargeId] ?? 0;
                } catch (\Throwable $th) {
                    $chargeAmount = 0;
                }
                if ($chargeAmount) {
                    $ID = (int) $this->taxCreditServices->TaxCreditInvoiceExists(
                        $this->TAX_CREDIT_ID,
                        $chargeId
                    );
                    if ($ID > 0) {
                        $this->taxCreditServices->UpdateInvoice(
                            $ID,
                            $this->TAX_CREDIT_ID,
                            $chargeId,
                            $chargeAmount
                        );
                        $this->invoiceServices->updateInvoiceBalance($chargeId);
                    } else {
                        $data = $this->invoiceServices->get($chargeId);
                        if ($data) {
                            $ACCOUNTS_RECEIVABLE_ID = $data->ACCOUNTS_RECEIVABLE_ID ?? 0;

                            $this->taxCreditServices->StoreInvoice(
                                $this->TAX_CREDIT_ID,
                                $chargeId,
                                $chargeAmount,
                                $ACCOUNTS_RECEIVABLE_ID
                            );
                            $this->invoiceServices->updateInvoiceBalance($chargeId);
                        }
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
        $this->invoiceList = $this->invoiceServices->getInvoiceListViaPayment(
            $this->CUSTOMER_ID,
            $this->LOCATION_ID,
            $this->TAX_CREDIT_ID
        );
        return view('livewire.tax-credit.invoice-list-modal');
    }
}
