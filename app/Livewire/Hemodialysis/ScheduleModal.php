<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use Illuminate\Support\Carbon;
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
    public function boot(ScheduleServices $scheduleServices, HemoServices $hemoServices, ShiftServices $shiftServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemoServices = $hemoServices;
        $this->shiftServices = $shiftServices;
    }
    public function create()
    {
        $isDone = false;
        $this->ids = "";

        foreach ($this->scheduleSelected as $scheId => $isSelect) {
            if ($isSelect) {
                $data = $this->scheduleServices->getInfo($scheId);
                if ($data) {
                    $id = (int) $this->hemoServices->PreSave(Carbon::now()->format('Y-m-d'), "", $data->CONTACT_ID, $this->LOCATION_ID);
                    if ($this->ids == "") {
                        $this->ids = $id;
                    } else {
                        $this->ids = $this->ids . "," . $id;
                    }

                    $isDone = true;
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
        $this->DATE = Carbon::now()->format('M/d/Y');
        $this->dataList = $this->scheduleServices->GetScheduleList(Carbon::now()->format('Y-m-d'), $this->LOCATION_ID, $this->SHIFT_ID);

        return view('livewire.hemodialysis.schedule-modal');
    }
}
