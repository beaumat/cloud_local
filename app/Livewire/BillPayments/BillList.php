<?php

namespace App\Livewire\BillPayments;

use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BillList extends Component
{
    #[Reactive]
    public int $CHECK_ID;
    public $dataList = [];
    public int $STATUS;
    public int $openStatus;
    private $billPaymentServices;

    #[Reactive]
    public int $VENDOR_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;


    public float $prevAmount;
    public float $orgAmount;
    public $editPaymentId = null;
    public int $editInvoiceId;

    public float $editAmountApplied;

    private $billingServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        BillingServices $billingServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->billingServices = $billingServices;
    }
    public function edit(int $ID, int $INVOICE_ID, float $Applied)
    {
        $this->editPaymentId = $ID;
        $this->editInvoiceId = $INVOICE_ID;
        $this->editAmountApplied = $Applied;
        $this->prevAmount = $Applied;
        $data = $this->billingServices->get($INVOICE_ID);
        if ($data) {
            $this->orgAmount = $data->AMOUNT;
        }
    }

    public function cancel()
    {
        $this->editPaymentId = null;

    }
    public function update()
    {
        // $RemainAmount = (float) $this->AMOUNT_APPLIED - $this->prevAmount;
        // if ($this->AMOUNT < ($RemainAmount + $this->editAmountApplied)) {
        //     session()->flash('error', 'invalid payment initial. the remaining payment to low.');
        //     return;
        // }

        // $totalPay = (float) $this->paymentServices->getTotalPay($this->editInvoiceId, $this->PAYMENT_ID);

        // $current_balance = (float) $this->orgAmount - $totalPay;

        // if ($current_balance < $this->editAmountApplied) {
        //     session()->flash('error', 'invalid payment initial is to high from invoice balance. please enter exactly initial amount');
        //     return;
        // }

        // $this->paymentServices->PaymentInvoiceUpdate($this->editPaymentId, $this->PAYMENT_ID, $this->editInvoiceId, 0, $this->editAmountApplied);
        // $this->invoiceServices->updateInvoiceBalance($this->editInvoiceId);
        // $this->editPaymentId = null;
        // $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $BILL_ID)
    {
        $this->billPaymentServices->PaymentInvoiceDelete($ID, $this->PAYMENT_ID, $BILL_ID);
        $this->billingServices->UpdateBalance($BILL_ID);
        $this->dispatch('reset-payment');
    }
    public function mount(int $CHECK_ID, int $VENDOR_ID, int $LOCATION_ID, float $AMOUNT)
    {
        $this->CHECK_ID = $CHECK_ID;
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->AMOUNT = $AMOUNT;

    }
    #[On('reload_payment_invoice')]
    public function render()
    {
        $this->dataList = $this->billPaymentServices->billPaymentBills($this->CHECK_ID);
        return view('livewire.bill-payments.bill-list');
    }
}
