<?php

namespace App\Livewire\UnitOfMeasurePage;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Unit of Measure List')]
class UnitOfMeasureList extends Component
{   
    public $unitOfMeasure = [];
    public $search = '';

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
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

        return view('livewire.unit-of-measure.unit-of-measure-list');
    }
}
