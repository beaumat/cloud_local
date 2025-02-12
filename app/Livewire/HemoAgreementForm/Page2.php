<?php

namespace App\Livewire\HemoAgreementForm;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class Page2 extends Component
{
    #[Reactive()]
    public int $HEMO_ID;
    public function render()
    {
        return view('livewire.hemo-agreement-form.page2');
    }
}
