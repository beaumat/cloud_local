<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class CustomCheckBox extends Component
{   
    #[Modelable]
    public $value = null;
    public string $name;
    public string $titleName;

    public function mount($name, $titleName)
    {
        $this->titleName = $titleName;
        $this->name = $name;
    }

    public function render()
    {
        return view('livewire.custom-check-box');
    }
}
