<?php

namespace App\Livewire\Scheduler;

use App\Services\HemodialysisMachineServices;
use App\Services\ScheduleServices;
use Livewire\Component;

class PrintWeeklyNames extends Component
{
    public $i = 1;
    public $contactList = [];
    private $scheduleServices;
    private $hemodialysisMachineServices;
    private int $totalCapacity = 0;
    public function boot(ScheduleServices $scheduleServices, HemodialysisMachineServices $hemodialysisMachineServices)
    {
        $this->scheduleServices = $scheduleServices;
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
        
    }

    public function mount($date, $shift, $location)
    {
        $n = 0;
        $this->totalCapacity = 0;
        $type = $this->hemodialysisMachineServices->GetList($location);

        foreach ($type as $item) {
            $this->totalCapacity = $this->totalCapacity + (int) $item->CAPACITY;

            $data = $this->scheduleServices->scheduleListByShift($date, $location, $shift, $item->ID);

            foreach ($data as $dataList) {
                $this->contactList[$n] = ['ID' => $n + 1, 'NAME' => $dataList->CONTACT_NAME, 'TYPE' => $item->ID];
                $n++;
            }

            if ($this->totalCapacity > $n) {
                for ($r = $n; $r < $this->totalCapacity; $r++) {
                    $this->contactList[$n] = ['ID' => $n + 1, 'NAME' => '', 'TYPE' => $item->ID];
                    $n++;
                }

            }


        }
    }
    public function render()
    {
        return view('livewire.scheduler.print-weekly-names');
    }
}
