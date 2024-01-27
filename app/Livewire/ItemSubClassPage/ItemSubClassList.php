<?php

namespace App\Livewire\ItemSubClassPage;

use App\Services\ItemSubClassServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Item Sub-Class - List')]
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
        return view('livewire.item-sub-class.item-sub-class-list');
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
