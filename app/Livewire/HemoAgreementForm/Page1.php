<?php

namespace App\Livewire\HemoAgreementForm;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class Page1 extends Component
{   
   #[Reactive()]
    public int $HEMO_ID;
    public function render()
    {
        return view('livewire.hemo-agreement-form.page1');
    }
}
