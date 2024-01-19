<?php

namespace App\Livewire;

use App\Models\PriceLevels;
use Livewire\Component;

class ItemPriceLevelPanel extends Component
{
    public int $itemId = 0;

    public int $ID;
    public int $PRICE_LEVEL_ID;
    public float $CUSTOM_PRICE;
    public $priceLevels = [];
    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->priceLevels = PriceLevels::query()->select('ID','DESCRIPTION')->where('INACTIVE','0')->get();
    }
    public function render()
    {
        return view('livewire.item-price-level-panel');
    }
}
