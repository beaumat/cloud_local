<?php

namespace App\Livewire\Scheduler;

use App\Models\Schedules;
use App\Services\ScheduleServices;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ShiftList extends Component
{
    private $scheduleServices;
    public $Date;
    public int $LOCATION_ID;
    public int $STATUS_ID;
    public $totalPatientsByShift = [];
    public function boot(ScheduleServices $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }
   
    public function mount($date, $location_id)
    {
        $this->Date = $date;
        $this->LOCATION_ID = $location_id;
      
    }
    public function getList()
    {
        $this->totalPatientsByShift = Schedules::query()
            ->select(
               
                'schedules.SHIFT_ID',
                DB::raw('IF(schedules.SCHED_STATUS = 0, COUNT(*), 0) AS W'),
                DB::raw('IF(schedules.SCHED_STATUS = 1, COUNT(*), 0) AS P'),
                DB::raw('IF(schedules.SCHED_STATUS = 2, COUNT(*), 0) AS A'),
                DB::raw('IF(schedules.SCHED_STATUS = 3, COUNT(*), 0) AS C')
            )
            ->join('contact AS c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->join('shift AS s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $this->Date)
            ->where('schedules.LOCATION_ID', $this->LOCATION_ID)
            ->groupBy(['schedules.SHIFT_ID','schedules.SCHED_STATUS'])
            ->get();
    }
    public function render()
    {
        $this->getList();
        return view('livewire.scheduler.shift-list');
    }
}
