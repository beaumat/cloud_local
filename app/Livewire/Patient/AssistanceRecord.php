<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AssistanceRecord extends Component
{   
   
    #[Reactive]
    public int $CONTACT_ID;
    public function render()
    {
        return view('livewire.patient.assistance-record');
    }
}
