<?php

namespace App\Livewire;

use App\Models\Locations;
use Livewire\Component;

class ItemInventoryPanel extends Component
{
    public int $itemId = 0;

    public int $ID;
    public int $LOCATION_ID;
    public float $ORDER_POINT;
    public float $ORDER_QTY;
    public int $ORDER_LEADTIME;
    public float $ONHAND_MAX_LIMIT;
    public int $STOCK_BIN_ID;

    public $locations = [];
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->locations = Locations::all();
    }
    public function render()
    {
        return view('livewire.item-inventory-panel');
    }
}
