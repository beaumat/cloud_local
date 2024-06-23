<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentList extends Component
{


    #[Reactive]
    public int $PHILHEALTH_ID;
    public $paymentList = [];
    private $philHealthServices;
    public $RECEIVED_DATE;
    public int $i = 0;
    public float $AMOUNT;
    public string $REF_NO;
    public float $P1_TOTAL = 0;
    public float $PAYMENT_AMOUNT = 0;
    public float $BALANCE = 0;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }
    public function ClearInsert()
    {
        $this->RECEIVED_DATE = null;
        $this->AMOUNT = 0;
    }
    public $editId = null;
    public string $editReceivedDate;
    public float $editAmount;
    public string $editRefNo;
    public function Store()
    {

        $this->validate(
            [
                'RECEIVED_DATE' => 'required',
                'REF_NO' => 'required',
                'AMOUNT' => 'required|not_in:0'
            ],
            [],
            [
                'RECEIVED_DATE' => 'Receive Date',
                'REF_NO' => 'OR No.',
                'AMOUNT' => 'Amount'
            ]
        );
        $this->philHealthServices->PaymentStore($this->PHILHEALTH_ID, $this->RECEIVED_DATE, $this->AMOUNT, $this->REF_NO);
        $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID);
        $this->RECEIVED_DATE  = null;
        $this->AMOUNT = 0;
    }
    public function Edit(int $ID)
    {
        $data = $this->philHealthServices->PaymentGet($ID);
        if ($data) {
            $this->editId = $data->ID;
            $this->editReceivedDate = $data->RECEIVED_DATE;
            $this->editAmount = $data->AMOUNT;
            $this->editRefNo = $data->REF_NO ?? '';
        }
    }
    public function Cancel()
    {
        $this->editId =  null;
        $this->editReceivedDate = '';
        $this->editAmount = 0;
        $this->editRefNo = '';
    }
    public function Update()
    {


        $this->validate(
            [

                'editReceivedDate' => 'required',
                'editAmount' => 'required|not_in:0',
                'editRefNo' => 'required'
            ],
            [],
            [
                'editReceivedDate' => 'Receive Date',
                'editAmount' => 'Amount',
                'editRefNo' => 'OR No.'
            ]
        );

        $this->philHealthServices->PaymentUpdate($this->editId, $this->PHILHEALTH_ID, $this->editReceivedDate, $this->editAmount, $this->editRefNo);
        $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID);
        $this->Cancel();
    }
    public function Delete(int $ID)
    {
        $this->philHealthServices->PaymentDelete($ID, $this->PHILHEALTH_ID);
        $this->philHealthServices->UpdatePayment($this->PHILHEALTH_ID);
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
    public function getAmount()
    {
        $data = $this->philHealthServices->get($this->PHILHEALTH_ID);
        if ($data) {
            $this->P1_TOTAL = $data->P1_TOTAL ?? 0;
            $this->PAYMENT_AMOUNT = $data->PAYMENT_AMOUNT ?? 0;
            $this->BALANCE =   $this->P1_TOTAL -  $this->PAYMENT_AMOUNT;
        }
    }
    public function render()
    {

        $this->getAmount();

        $this->paymentList = $this->philHealthServices->paymentList($this->PHILHEALTH_ID);
        return view('livewire.phil-health.payment-list');
    }
}
