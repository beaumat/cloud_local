<?php

namespace App\Livewire\Invoice;

use App\Services\InvoiceServices;
use App\Services\PaymentServices;
use App\Services\TaxCreditServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class QuickPaid extends Component
{
    #[Reactive]
    public $LOCATION_ID;

    public $search;
    public $dataList = [];
    public bool $showModal = false;
    private $invoiceServices;
    private $paymentServices;
    private $taxCreditServices;
    public function boot(InvoiceServices $invoiceServices, PaymentServices $paymentServices, TaxCreditServices $taxCreditServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->paymentServices = $paymentServices;
        $this->taxCreditServices = $taxCreditServices;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openARform(int $PHILHEALTH_ID)
    {
        $this->dispatch('ar-form-show', result: ['PHILHEALTH_ID' => $PHILHEALTH_ID]);
    }
    public function makePaid(int $INVOICE_ID)
    {

        $data = [
            'INVOICE_ID' => $INVOICE_ID
        ];
        $this->dispatch('quick-paid', result: $data);
    }
    #[On('quick-paid-reload')]
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->invoiceServices->getActiveList($this->search, $this->LOCATION_ID);
        } else {
            $this->dataList = [];
        }

        return view('livewire.invoice.quick-paid');
    }
}
