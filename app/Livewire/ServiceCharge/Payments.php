<?php

namespace App\Livewire\ServiceCharge;

use App\Services\InvoiceServices;
use App\Services\PaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Payments extends Component
{

    #[Reactive]
    public int $INVOICE_ID;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public int $openStatus = 0;
    public $data = [];
    private $paymentServices;
    private $invoiceServices;
    public function boot(PaymentServices $paymentServices, InvoiceServices $invoiceServices)
    {
        $this->paymentServices = $paymentServices;
        $this->invoiceServices = $invoiceServices;

    }
    public function delete(int $ID, int $PAYMENT_ID)
    {
        $this->paymentServices->PaymentInvoiceDelete($ID, $PAYMENT_ID, $this->INVOICE_ID);
        $this->paymentServices->UpdatePaymentApplied($PAYMENT_ID);
        $this->invoiceServices->updateInvoiceBalance($this->INVOICE_ID);
        $getResult = $this->invoiceServices->ReComputed($this->INVOICE_ID);
        $this->dispatch('update-amount', result: $getResult);
        $this->dispatch('update-status');
    }
    public function render()
    {
        $this->data = $this->paymentServices->InvoicePaymentList($this->INVOICE_ID, $this->CUSTOMER_ID);
        return view('livewire.service-charge.payments');
    }

}
