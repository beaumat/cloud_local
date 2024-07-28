<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AssistanceRecordBalance extends Component
{   
    #[Reactive]
    public int $CONTACT_ID;
    public $dataList = [];
    public function render()
    {   
        
        return view('livewire.patient.assistance-record-balance');
    }
}
