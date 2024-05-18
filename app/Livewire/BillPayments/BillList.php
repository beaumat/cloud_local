<?php

namespace App\Livewire\BillPayments;

use App\Services\BillingServices;
use App\Services\BillPaymentServices;
use Livewire\Attributes\On;
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
    #[Reactive]
    public float $AMOUNT_APPLIED;
    public float $prevAmount;
    public float $orgAmount;
    public $editPaymentId = null;
    public int $editBill_Id;
    public float $editAmountApplied;

    private $billingServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        BillingServices $billingServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->billingServices = $billingServices;
    }
    public function edit(int $ID, int $BILL_ID, float $Applied)
    {
        $this->editPaymentId = $ID;
        $this->editBill_Id = $BILL_ID;
        $this->editAmountApplied = $Applied;
        $this->prevAmount = $Applied;
        $data = $this->billingServices->get($BILL_ID);
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
        $RemainAmount = (float) $this->AMOUNT_APPLIED - $this->prevAmount;
        if ($this->AMOUNT < ($RemainAmount + $this->editAmountApplied)) {
            session()->flash('error', 'Invalid payment initial. the remaining bill payment to low.');
            return;
        }
        $totalPay = (float) $this->billPaymentServices->getTotalPay($this->editBill_Id, $this->CHECK_ID);
        $current_balance = (float) $this->orgAmount - $totalPay;
        if ($current_balance < $this->editAmountApplied) {
            session()->flash('error', 'invalid bill payment initial is to high from billing balance. please enter exactly initial amount');
            return;
        }
        $this->billPaymentServices->billPaymentBills_Update($this->editPaymentId, $this->CHECK_ID, $this->editBill_Id, 0, $this->editAmountApplied);
        $this->billingServices->UpdateBalance($this->editBill_Id);
        $this->editPaymentId = null;
        $this->dispatch('reset-payment');
    }
    public function delete(int $ID, int $BILL_ID)
    {
        $this->billPaymentServices->billPaymentBills_Delete($ID, $this->CHECK_ID, $BILL_ID);
        $this->billingServices->UpdateBalance($BILL_ID);
        $this->dispatch('reset-payment');
    }
    public function mount(int $CHECK_ID, int $VENDOR_ID, int $LOCATION_ID, float $AMOUNT, float $AMOUNT_APPLIED)
    {
        $this->CHECK_ID = $CHECK_ID;
        $this->VENDOR_ID = $VENDOR_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->AMOUNT = $AMOUNT;
        $this->AMOUNT_APPLIED = $AMOUNT_APPLIED;
    }
    #[On('reload_bill_list')]
    public function render()
    {
        $this->dataList = $this->billPaymentServices->billPaymentBills($this->CHECK_ID);

        return view('livewire.bill-payments.bill-list');
    }
}
