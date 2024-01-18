<?php

namespace App\Livewire;

use Livewire\Component;

class ItemInventoryPanel extends Component
{
    public int $itemId = 0;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
    }
    public function render()
    {
        return view('livewire.item-inventory-panel');
    }
}
