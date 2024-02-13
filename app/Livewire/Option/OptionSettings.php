<?php

namespace App\Livewire\Option;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Option Settings')]
class OptionSettings extends Component
{
    public function render()
    {
        return view('livewire.option.option-settings');
    }
}
