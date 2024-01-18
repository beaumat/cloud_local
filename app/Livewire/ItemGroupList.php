<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\ItemGroupServices;

class ItemGroupList extends Component
{
    public $itemGroup = [];
    public $search = '';

    public function updatedsearch(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
    }
    public function delete($id, ItemGroupServices $itemGroupServices)
    {
        try {
            $itemGroupServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->itemGroup =  $itemGroupServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
    }
    public function render(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
        return view('livewire.item-group-list');
    }
}
