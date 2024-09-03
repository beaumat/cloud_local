<?php

namespace App\Livewire\PhilHealth;

use Livewire\Component;

class PrintCf2 extends Component
{   

    public $DATE_ADMITTED;
    public $TIME_ADMITTED;

    public function render()
    {
        return view('livewire.phil-health.print-cf2');
    }
}
