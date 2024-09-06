<?php

namespace App\Livewire\PhilHealth;

use Livewire\Component;

class PrintCf2Back extends Component
{   
    public $TIME_ADMITTED;
    public $DATE_ADMITTED;
    public $DATE_DISCHARGED;
    public $DR_NAME;
    public function render()
    {
        return view('livewire.phil-health.print-cf2-back');
    }
}
