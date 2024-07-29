<?php

namespace App\Livewire\PatientPayment;

use Livewire\Attributes\On;
use Livewire\Component;

class PaymentRecordModal extends Component
{
    public $showModal = false;
    public int $CONTACT_ID = 0;

    #[On('open-assistance')]
    public function openModal($result)
    {
        $this->CONTACT_ID = $result['CONTACT_ID'];
        $this->showModal = true;
    }
    public function closeModal()
    {
   
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.patient-payment.payment-record-modal');
    }
}
