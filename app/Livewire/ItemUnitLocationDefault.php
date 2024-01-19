<?php

namespace App\Livewire;

use App\Models\Locations;
use App\Models\UnitOfMeasures;
use Livewire\Component;

class ItemUnitLocationDefault extends Component
{
    public int $ITEM_ID;
    public int $UNIT_LOCATION_ID;
    public int $UNIT_PURCHASES_UNIT_ID;
    public int $UNIT_SALES_UNIT_ID;
    public int $UNIT_SHIPPING_UNIT_ID;

    public $locationList = [];
    public $unitList = [];
    public function mount($ITEM_ID)
    {
        $this->ITEM_ID = $ITEM_ID;
        $this->locationList = Locations::where('INACTIVE','0')->get();
        $this->unitList = UnitOfMeasures::where('INACTIVE','0')->get();
    }   



    public function render()
    {
        return view('livewire.item-unit-location-default');
    }
}
