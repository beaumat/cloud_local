<?php

namespace App\Livewire;

use App\Models\ItemClass;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use App\Services\ItemClassServices;

class ItemClassForm extends Component
{

    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $itemClass = ItemClass::where('ID', $id)->first();

            if ($itemClass) {
                $this->ID = $itemClass->ID;
                $this->CODE = $itemClass->CODE;
                $this->DESCRIPTION = $itemClass->DESCRIPTION;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryitem_class')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
    }


    public function save(ItemClassServices $itemClassServices)
    {
        $this->validate([
            'CODE' => 'required|max:10|unique:item_class,code,' . $this->ID,
            'DESCRIPTION' => 'required|max:100|unique:item_class,description,' . $this->ID
        ]);
        try {
            if ($this->ID === 0) {
                $this->ID = $itemClassServices->Store($this->CODE, $this->DESCRIPTION);
                session()->flash('message', 'Successfully created.');
            } else {
                $itemClassServices->Update($this->ID, $this->CODE, $this->DESCRIPTION);
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.item-class-form');
    }
}
