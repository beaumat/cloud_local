<?php

namespace App\Livewire\ItemPage;

use App\Services\ItemServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Item List')]
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
        return view('livewire.item-page.items-list');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
}
