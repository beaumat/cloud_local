<?php

namespace App\Livewire\Deposit;

use App\Services\DepositServices;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentListModal extends Component
{
    public int $UNDEPOSITED_ACCOUNT_ID = 5;
    public int $PAYMENT_METHOD_ID = 0;
    public int $LOCATION_ID;
    public $dataList = [];
    private $depositServices;
    public function boot(DepositServices $depositServices)
    {
        $this->depositServices = $depositServices;
    }

    public $showModal = false;
    #[On('open-payment')]
    public function openModal($result)
    {
        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->PAYMENT_METHOD_ID = 0;
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->depositServices->getUndositedCollection($this->LOCATION_ID, $this->PAYMENT_METHOD_ID);
        }

        return view('livewire.deposit.payment-list-modal');
    }
}
