<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UnitOfMeasures;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\Redirect;

class UnitOfMeasureForm extends Component
{


    public int $ID;
    public string $NAME;
    public string $SYMBOL;
    public bool $INACTIVE;

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $UOM = UnitOfMeasures::where('ID', $id)->first();

            if ($UOM) {
                $this->ID = $UOM->ID;
                $this->NAME = $UOM->NAME;
                $this->SYMBOL = $UOM->SYMBOL;
                $this->INACTIVE = $UOM->INACTIVE;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryunit_of_measure')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->SYMBOL = '';
        $this->INACTIVE = false;
    }


    public function save(UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->validate([
            'NAME' => 'required|max:20|unique:unit_of_measure,name,' . $this->ID,
            'SYMBOL' => 'required|max:15|unique:unit_of_measure,symbol,' . $this->ID
        ]);

        try {
            if ($this->ID === 0) {
               $this->ID = $unitOfMeasureServices->Store($this->NAME, $this->SYMBOL, $this->INACTIVE);
                session()->flash('message', 'Successfully created.');
            } else {
                $unitOfMeasureServices->Update($this->ID, $this->NAME, $this->SYMBOL, $this->INACTIVE);
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.unit-of-measure-form');
    }
}
