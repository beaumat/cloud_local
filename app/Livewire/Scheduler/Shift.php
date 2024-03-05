<?php

namespace App\Livewire\Scheduler;

use App\Services\ScheduleServices;
use Livewire\Component;

class Shift extends Component
{
    public $ID;
    public int $CONTACT_ID;
    public $shiftList = [];
    public int $SHIFT_ID = 0;
    private $scheduleServices;
    public int $LOCATION_ID;
    public int $STATUS_ID;
    public function boot(ScheduleServices $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }
    public function mount($id, $contact_id, $shiftList, $location_id)
    {
        $this->ID = $id; // is date
        $this->CONTACT_ID = $contact_id;
        $this->$shiftList = $shiftList;
        $this->LOCATION_ID = $location_id;
        $schedule = $this->scheduleServices->get($this->CONTACT_ID, $id, $this->LOCATION_ID);
        if($schedule) {
            $this->SHIFT_ID = $schedule->SHIFT_ID;
            $this->STATUS_ID = $schedule->SCHED_STATUS;
        }
       
    }

    public function save(int $shift_id, $date)
    {

        if ($this->CONTACT_ID > 0) {
            try {
                $schedule = $this->scheduleServices->get($this->CONTACT_ID, $date, $this->LOCATION_ID);
                if ($schedule) {
                
                    if ($shift_id == 0) {
                        $this->scheduleServices->Delete($schedule->id, $this->LOCATION_ID);
                    } else {
                        $this->scheduleServices->Update($this->CONTACT_ID, $date, $shift_id, $schedule->SCHED_STATUS, $schedule->STATUS_LOG, $this->LOCATION_ID);
                    }
                } elseif ($shift_id != 0) {
                    $this->scheduleServices->Store($shift_id, $this->CONTACT_ID, $date, 0, null, $this->LOCATION_ID);
                }
                $this->dispatch('load-schedule-by-contact');
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        }

    }

    public function render()
    {
        return view('livewire.scheduler.shift');
    }
}
