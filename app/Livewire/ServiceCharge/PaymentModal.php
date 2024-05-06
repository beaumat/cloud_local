<?php

namespace App\Livewire\ServiceCharge;

use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PaymentModal extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $SERVICE_CHARGES_ID;
    public string $tab = 'available';
 
    public function SelectTab(string $select)
    {
        $this->tab = $select;
    }
    public bool $showModal;
    public function openModal()
    {
        $this->showModal = true;
    }
    #[On('payment-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
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
        return view('livewire.service-charge.payment-modal');
    }
}
