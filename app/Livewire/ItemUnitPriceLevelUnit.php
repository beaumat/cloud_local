<?php

namespace App\Livewire;

use App\Models\PriceLevels;
use Livewire\Component;

class ItemUnitPriceLevelUnit extends Component
{
    public int $ITEM_ID;
    public int $UNIT_PRICE_LEVEL_ID;
    public float $CUSTOM_PRICE;

    public int $UNIT_RELATED_ID;
    public $unitRelated = [];
    public $priceLevels = [];
    public function mount($ITEM_ID)
    {
        $this->ITEM_ID = $ITEM_ID;
        $this->priceLevels = PriceLevels::where('INACTIVE', '0')->get();
    }
    public function render()
    {
        return view('livewire.item-unit-price-level-unit');
    }
}
