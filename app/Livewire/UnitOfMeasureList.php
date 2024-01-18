<?php

namespace App\Livewire;

use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;

class UnitOfMeasureList extends Component
{
    public $unitOfMeasure = [];
    public $search = '';

    public function updatedsearch(UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->unitOfMeasure = $unitOfMeasureServices->Search($this->search);
    }
    public function delete($id, UnitOfMeasureServices $unitOfMeasureServices)
    {
        try {
            $unitOfMeasureServices->Delete($id);
            session()->flash('message', 'Successfully deleted');
            $this->unitOfMeasure = $unitOfMeasureServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->unitOfMeasure = $unitOfMeasureServices->Search($this->search);
    }
    public function render()
    {

        return view('livewire.unit-of-measure-list');
    }
}
