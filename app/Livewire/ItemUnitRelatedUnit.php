<?php

namespace App\Livewire;

use App\Models\UnitOfMeasures;
use Livewire\Component;

class ItemUnitRelatedUnit extends Component
{
    public int $ID;
    public int $ITEM_ID;
    public int $UNIT_ID;
    public float $QUANTITY;
    public float $RATE;
    public string $BARCODE;
    public $units = [];
    public function mount($ITEM_ID = 0)
    {   
        $this->ITEM_ID = $ITEM_ID;
        $this->units =  UnitOfMeasures::where('INACTIVE', '0')->get();
    }
    public function render()
    {
        return view('livewire.item-unit-related-unit');
    }
}
