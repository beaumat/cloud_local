<?php

namespace App\Livewire\HemoAgreementForm;

use Livewire\Component;

class AgreementPrint extends Component
{
    public int $HEMO_ID;
    public function mount($id = null) {

        $this->HEMO_ID = (int) $id;
    }

    public function render()
    {
        return view('livewire.hemo-agreement-form.agreement-print');
    }
}
