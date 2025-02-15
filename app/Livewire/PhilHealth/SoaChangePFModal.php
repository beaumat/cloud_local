<?php

namespace App\Livewire\PhilHealth;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class SoaChangePFModal extends Component
{   
    #[Reactive]
    public $PHILHEALTH_ID;

    public function boot() {
        
    }

    public function render()
    {
        return view('livewire.phil-health.soa-change-p-f-modal');
    }
}
