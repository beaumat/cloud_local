<?php

namespace App\Livewire\Scheduler;

use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Component;

class ShiftMonitoring extends Component
{

    public $i = 1;
    public $contactList = [];
    private $scheduleServices;
    private $hemodialysisMachineServices;
    private int $totalCapacity = 0;

    public $SHIFT_NAME;
    private $shiftServices;
    public function boot(ScheduleServices $scheduleServices, HemodialysisMachineServices $hemodialysisMachineServices, ShiftServices $shiftServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        $this->shiftServices = $shiftServices;
    }

    public $showModal;
    public int $SHIFT_ID;
    public int $CONTACT_ID;
    public int $LOCATION_ID;
    public string $DATE;
    private function LoadData()
    {
        $n = 0;
        $this->totalCapacity = 0;
        $type = $this->hemodialysisMachineServices->GetList($this->LOCATION_ID);

        foreach ($type as $item) {
            $this->totalCapacity = $this->totalCapacity + (int) $item->CAPACITY;
            $data = $this->scheduleServices->scheduleListByShift($this->DATE, $this->LOCATION_ID, $this->SHIFT_ID, $item->ID);
            foreach ($data as $dataList) {
                $this->contactList[$n] = ['ID' => $n + 1, 'NAME' => $dataList->CONTACT_NAME, 'TYPE' => $item->ID, 'CONTACT_ID' => $dataList->CONTACT_ID];
                $n++;
            }

            if ($this->totalCapacity > $n) {
                for ($r = $n; $r < $this->totalCapacity; $r++) {
                    $this->contactList[$n] = ['ID' => $n + 1, 'NAME' => '', 'TYPE' => $item->ID, 'CONTACT_ID' => '0'];
                    $n++;
                }
            }
        }


        $data = $this->shiftServices->get($this->SHIFT_ID);
        if ($data) {
            $this->SHIFT_NAME = $data->NAME;
        } else {
            $this->SHIFT_NAME = '';
        }
    }


    #[On('open-shift-monitoring')]
    public function getList($reglist)
    {
        $this->SHIFT_ID = $reglist['SHIFT_ID'];
        $this->LOCATION_ID = $reglist['LOCATION_ID'];
        $this->CONTACT_ID = $reglist['CONTACT_ID'];
        $this->DATE = $reglist['DATE'];
        $this->showModal = true;
        $this->LoadData();
    }


    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {

        return view('livewire.scheduler.shift-monitoring');
    }
}
