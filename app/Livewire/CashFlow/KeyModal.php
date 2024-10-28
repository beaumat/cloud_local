<?php

namespace App\Livewire\CashFlow;

use Livewire\Attributes\On;
use Livewire\Component;

class KeyModal extends Component
{
    public $showModal = false;
    #[On('open-cf-key')]
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.cash-flow.key-modal');
    }
}
