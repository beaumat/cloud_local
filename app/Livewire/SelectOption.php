<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SelectOption extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
    public string $titleName;
    #[Reactive]
    public $options = [];
    public bool $zero;
    public bool $vertical;
    public function mount($name, $options, $zero, $titleName, $vertical = false)
    {
        $this->titleName = $titleName;
        $this->zero = $zero;
        $this->name = $name;
        $this->options = $options;
        $this->vertical = $vertical;
    }

    public function render()
    {
        return view('livewire.select-option');
    }
}
