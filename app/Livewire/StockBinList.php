<?php

namespace App\Livewire;
use App\Services\StockBinServices;
use Livewire\Component;

class StockBinList extends Component
{

    public $stockBin = [];
    public $search = '';
    public function updatedsearch(StockBinServices $stockBinServices)
    {
        $this->stockBin = $stockBinServices->Search($this->search);
    }
    public function delete($id, StockBinServices $stockBinServices)
    {
        try {
            $stockBinServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->stockBin = $stockBinServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(StockBinServices $stockBinServices)
    {
        $this->stockBin = $stockBinServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.stock-bin-list');
    }
}
