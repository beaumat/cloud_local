<?php

namespace App\Livewire;

use Livewire\Component;

class ItemComponentPanel extends Component
{
    public int $itemId = 0;
    public int $itemType;
    public function mount($itemId, $itemType = 0)
    {
        $this->itemId = $itemId;
        $this->itemType = $itemType;
    }

    public function render()
    {
        return view('livewire.item-component-panel');
    }
}
