<?php

namespace App\Livewire\Location;

use App\Models\LocationGroup;
use App\Models\Locations;
use App\Models\PriceLevels;
use App\Services\LocationServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location - Form')]
class LocationForm extends Component
{
    public int $ID;
    public string $NAME;
    public bool $INACTIVE;
    public int $PRICE_LEVEL_ID;
    public int $GROUP_ID;
    public $priceLevels = [];
    public $locationGroups = [];

    public function loadDropDown()
    {
        $this->priceLevels = PriceLevels::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->where('TYPE', '0')->get();
        $this->locationGroups = LocationGroup::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
    }
    public function mount($id = null)
    {
        $this->loadDropDown();
        if (is_numeric($id)) {
            $location = Locations::where('ID', $id)->first();

            if ($location) {
                $this->ID = $location->ID;
                $this->NAME = $location->NAME;
                $this->INACTIVE = $location->INACTIVE;
                $this->PRICE_LEVEL_ID = $location->PRICE_LEVEL_ID ? $location->PRICE_LEVEL_ID : 0;
                $this->GROUP_ID = $location->GROUP_ID ? $location->GROUP_ID : 0;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingslocation')->with('error', $errorMessage);
        }
        $this->ID = 0;
        $this->NAME = '';
        $this->PRICE_LEVEL_ID = 0;
        $this->GROUP_ID = 0;
        $this->INACTIVE = false;
    }


    public function save(LocationServices $locationServices)
    {
        $this->validate([
  
            'NAME' => 'required|max:50|unique:location,name,' . $this->ID
        ], [], [
      
            'NAME' => 'Name'
        ]);

        try {
            if ($this->ID === 0) {
                $this->ID = $locationServices->Store($this->NAME, $this->INACTIVE, $this->PRICE_LEVEL_ID, $this->GROUP_ID);
                session()->flash('message', 'Successfully created');
            } else {
                $locationServices->Update($this->ID, $this->NAME, $this->INACTIVE, $this->PRICE_LEVEL_ID, $this->GROUP_ID);
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.location.location-form');
    }
}
