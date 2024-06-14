<?php

namespace App\Livewire\Hemodialysis;

use App\Models\HemodialysisItems;
use App\Services\HemoServices;
use App\Services\ItemTreatmentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemTreatment extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $LOCATION_ID;

    public $search;
    private $itemTreatmentServices;
    private $hemoServices;
    public function boot(ItemTreatmentServices $itemTreatmentServices, HemoServices $hemoServices)
    {
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->hemoServices = $hemoServices;
    }
    public function addItem(int $ItemTreatmentId)
    {

        $data = $this->itemTreatmentServices->Get($ItemTreatmentId);

        if ($data) {
            $gotNew = true;
            if ($data->NO_OF_USED > 1) {
                $hemoData =  $this->hemoServices->Get($this->HEMO_ID);
                if ($hemoData) {
                    $totalused = (int)  $this->hemoServices->getItemTotalUsed($data->ITEM_ID, $this->LOCATION_ID, $hemoData->CUSTOMER_ID, $hemoData->DATE);
                    if ($totalused == 0) {
                        $gotNew = true;
                    } elseif ($totalused < $data->NO_OF_USED) {
                        $gotNew = false;
                    }
                }
            }


            $this->hemoServices->ItemStore($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, 1, $gotNew);
            $this->dispatch('refresh-item-treatment');
        }
    }
    public function render()
    {
        $dataList = $this->itemTreatmentServices->SearchHemo($this->search, $this->LOCATION_ID, $this->HEMO_ID);

        return view('livewire.hemodialysis.item-treatment', ['dataList' => $dataList]);
    }
}
