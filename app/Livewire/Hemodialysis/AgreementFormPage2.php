<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormPage2 extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public function render()
    {
        return view('livewire.hemodialysis.agreement-form-page2');
    }
}
