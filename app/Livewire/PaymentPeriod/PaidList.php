<?php

namespace App\Livewire\PaymentPeriod;

use App\Services\PaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaidList extends Component
{
    #[Reactive()]
    public int $PAYMENT_PERIOD_ID;

    #[Reactive]
    public float $GROSS_TOTAL;
    public $dataList = [];
    private $paymentServices;
    public function boot(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }
    private function loadData() {
        $this->dataList = $this->paymentServices->getListInvoicePaymentTaxBillPhic($this->PAYMENT_PERIOD_ID);
    }

    public function render()
    {   

        $this->loadData();
        return view('livewire.payment-period.paid-list');
    }
}
