<?php

namespace App\Livewire\PhilHealth;

use App\Services\ItemSoaServices;
use Livewire\Component;

class PrintItemized extends Component
{
    public string $date;
    public $dataList = [];
    public int $qty = 1;
    private $itemSoaServices;
    public function boot(ItemSoaServices $itemSoaServices)
    {
        $this->itemSoaServices = $itemSoaServices;
    }
    public function mount(int $num, int $locationid, string $date)
    {
        $this->date = $date;
        $this->qty = $num;
        $this->dataList = $this->itemSoaServices->GetList($locationid);
    }
    public function render()
    {
        return view('livewire.phil-health.print-itemized');
    }
}
