<?php

namespace App\Livewire;

use App\Services\ItemSubClassServices;
use Livewire\Component;
class ItemSubClassList extends Component
{

    public $itemSubClass = [];
    public $search = '';

    public function updatedsearch(ItemSubClassServices $itemSubClassServices)
    {
        $this->itemSubClass =  $itemSubClassServices->Search($this->search);
    }
    public function delete($id, ItemSubClassServices $itemSubClassServices)
    {
        try {
            $itemSubClassServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->itemSubClass =  $itemSubClassServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function mount(ItemSubClassServices $itemSubClassServices)
    {
        $this->itemSubClass =  $itemSubClassServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.item-sub-class-list');
    }
}
