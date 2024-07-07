<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemTreatmentServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
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
    private $unitOfMeasureServices;
    public function boot(ItemTreatmentServices $itemTreatmentServices, HemoServices $hemoServices, UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->hemoServices = $hemoServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
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
            try {
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
                $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
                $this->hemoServices->ItemStore($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew);
                // TRIGGER START
                $dataTrigger = $this->itemTreatmentServices->listItemTrigger($ItemTreatmentId);
                foreach ($dataTrigger  as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $this->hemoServices->ItemStore($this->HEMO_ID, $list->ITEM_ID, $list->QUANTITY, $list->UNIT_ID ?? 0, $TR_UNIT_BASE_QUANTITY, true);
                }
                // TRIGGER END

                $this->dispatch('refresh-item-treatment');
            } catch (\Throwable $th) {

                session()->flash('error', $th->getMessage());
            }
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $dataList = $this->itemTreatmentServices->SearchHemo($this->search, $this->LOCATION_ID, $this->HEMO_ID);
        return view('livewire.hemodialysis.item-treatment', ['dataList' => $dataList]);
    }
}
