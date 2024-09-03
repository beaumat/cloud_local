<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SelectCheckbox extends Component
{

    #[Modelable]
    public $value = null;
    public string $name;
    public string $titleName;
    #[Reactive]
    public $options = [];
    public bool $zero;
    public bool $vertical;
    public bool $withLabel;
    public bool $isDisabled;
    public $selectedOptions = []; // Array to store selected options

    public function updatedSelectedOptions()
    {
        // Logic to handle changes in selected options
    }
    public function mount($name, $options, $zero, $titleName, $vertical = false, $withLabel = true, $isDisabled = false)
    {
        $this->titleName = $titleName;
        $this->zero = $zero;
        $this->name = $name;
        $this->options = $options;
        $this->vertical = $vertical;
        $this->withLabel = $withLabel;
        $this->isDisabled = $isDisabled;
    }
    public function render()
    {
        return view('livewire.select-checkbox');
    }
}
