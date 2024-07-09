<?php

namespace App\Livewire\Hemodialysis;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\ItemTreatmentServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use App\Services\UnitOfMeasureServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ScheduleModal extends Component
{
    public bool $showModal;

    #[Reactive]
    public $LOCATION_ID;
    public $DATE;
    private $scheduleServices;
    private $hemoServices;
    public $dataList = [];
    public $scheduleSelected = [];
    public bool $SelectAll = false;
    public string $ids;
    public $shiftList = [];
    public int $SHIFT_ID = 0;
    private $shiftServices;
    private $dateServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    public function boot(ScheduleServices $scheduleServices, HemoServices $hemoServices, ShiftServices $shiftServices, DateServices $dateServices, ItemTreatmentServices $itemTreatmentServices, UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemoServices = $hemoServices;
        $this->shiftServices = $shiftServices;
        $this->dateServices = $dateServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }
    // public function addItem(int $ItemTreatmentId, int $ID)
    // {
    //     $data = $this->itemTreatmentServices->Get($ItemTreatmentId);
    //     if ($data) {
    //         $gotNew = true;
    //         $hemoData =  $this->hemoServices->Get($ID);
    //         if ($data->NO_OF_USED > 1) {

    //             if ($hemoData) {
    //                 $totalused = (int)  $this->hemoServices->getItemTotalUsed($data->ITEM_ID, $this->LOCATION_ID, $hemoData->CUSTOMER_ID, $hemoData->DATE);
    //                 if ($totalused == 0) {
    //                     $gotNew = true;
    //                 } elseif ($totalused < $data->NO_OF_USED) {
    //                     $gotNew = false;
    //                 }
    //             }
    //         }

    //         try {
    //             $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
    //             $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
    //             $this->hemoServices->ItemStore($ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew);
    //         } catch (\Throwable $th) {
    //             session()->flash('error', $th->getMessage());
    //         }
    //     }
    // }

    public function create()
    {
        $isDone = false;
        $this->ids = "";

        foreach ($this->scheduleSelected as $scheId => $isSelect) {
            if ($isSelect) {
                $data = $this->scheduleServices->getInfo($scheId);
                if ($data) {
                    try {
                        DB::beginTransaction();
                        $id = (int) $this->hemoServices->PreSave($this->DATE, "", $data->CONTACT_ID, $this->LOCATION_ID);
                        $hemoData =  $this->hemoServices->Get($id);
                        $dataList = $this->itemTreatmentServices->AutoItemList($this->LOCATION_ID);           // show add default items
                        foreach ($dataList as $item) {
                            $this->hemoServices->AddItem($item->ID,  $hemoData);
                        }

                        if ($this->ids == "") {
                            $this->ids = $id;
                        } else {
                            $this->ids = $this->ids . "," . $id;
                        }
                        DB::commit();
                        $isDone = true;
                    } catch (\Throwable $th) {
                        //throw $th;
                        DB::rollBack();
                    }
                }
            }
        }

        if ($isDone == false) {
            return;
        }

        $url = route('patientshemo_print', ['id' => $this->ids]);
        $this->dispatch('schedOpenNewTab', data: $url);
        $this->dispatch('refresh-list');
        $this->closeModal();
    }
    public function updatedSelectAll($value)
    {

        if ($value) {
            foreach ($this->dataList as $list) {
                $this->scheduleSelected[$list->ID] = true;
            }
        } else {

            $this->reset('scheduleSelected');
        }
    }
    public function openModal()
    {
        $this->DATE = $this->dateServices->NowDate();
        $this->showModal = true;
    }
    #[On('schedule-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        $this->shiftList = $this->shiftServices->List();
        $this->dataList = $this->scheduleServices->GetScheduleList($this->DATE, $this->LOCATION_ID, $this->SHIFT_ID);
        return view('livewire.hemodialysis.schedule-modal');
    }
}
