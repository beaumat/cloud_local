<?php

namespace App\Livewire;

use App\Services\ManufacturerServices;
use Livewire\Component;

class ManufacturerList extends Component
{
    public $manufacturer = [];
    public $search = '';

    public function updatedsearch(ManufacturerServices $manufacturerServices)
    {
        $this->manufacturer = $manufacturerServices->Search($this->search);
    }
    public function delete($id, ManufacturerServices $manufacturerServices)
    {
        try {

            $manufacturerServices->Delete($id);
            session()->flash('message','Successfully deleted.');
            $this->manufacturer = $manufacturerServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error',$errorMessage);
        }
    }
    public function mount(ManufacturerServices $manufacturerServices)
    {
        $this->manufacturer = $manufacturerServices->Search($this->search);
    }
    public function render()
    {

        return view('livewire.manufacturer-list');
    }
}
