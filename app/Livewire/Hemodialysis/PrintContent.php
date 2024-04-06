<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Component;

class PrintContent extends Component
    
{
    public array $collection = [1,2,3,4,5,6,7,8,9,10,11,12,13,14];

    public function render()
    {
        return view('livewire.hemodialysis.print-content');
    }
}
