<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class TextInput extends Component
{   
    #[Modelable]
    public $value = null;
    public string $name;
    public string $titleName;
    public bool $vertical;  
    public function mount($name, $titleName, $vertical = false)
    {
        $this->titleName = $titleName;
        $this->name = $name;
        $this->vertical = $vertical;
    }
    public function render()
    {
        return view('livewire.text-input');
    }
}