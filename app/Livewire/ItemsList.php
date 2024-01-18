<?php

namespace App\Livewire;

use App\Services\ItemServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class ItemsList extends Component
{

    public $items =[];
    public $search = '';
    public function updatedsearch(ItemServices $itemServices)
    {
        $this->items = $itemServices->Search($this->search);
    }
     public function delete($id,ItemServices $itemServices) {

        try {
            $itemServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->items = $itemServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
         
     }
     public function mount(ItemServices $itemServices)
     {
        $this->items = $itemServices->Search($this->search);
     }
    public function render()
    {        
        return view('livewire.items-list');
    }
}
