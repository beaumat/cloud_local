<?php

namespace App\Livewire\ServiceCharge;

use Livewire\Attributes\On;
use Livewire\Component;

class AgreementFormModal extends Component
{
    public $HEMO_ID;
    public bool $showModal = false;
    #[On('open-agreement-form')]
    public function openModal($data)
    {
        $this->showModal = true;
        $this->HEMO_ID = (int) $data['HEMO_ID'];

    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {

        return view('livewire.service-charge.agreement-form-modal');
    }
}
