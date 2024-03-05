<?php

namespace App\Livewire\Scheduler;

use Carbon\Carbon;
use Livewire\Component;

class CalendarList extends Component
{
    public $today;
    public int $year;
    public int $month;
    public Carbon $currentDate;
    public $startDayOfWeek;
    public $daysInMonth;
    public $daysInPreviousMonth;
    public int $dayCounter = 1;
    public int $LOCATION_ID;
    public string $contactName;
    public function mount(int $year, int $month, $locationid = null)
    {
        $this->year = $year;
        $this->month = $month;
        $this->LOCATION_ID = $locationid ? $locationid : 0;

    }
    public function getsched($date)
    {
        $Dt = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
      
        $this->dispatch('back-load', Date: $Dt);

    }
    private function reloadData()
    {
        $this->currentDate = Carbon::create($this->year, $this->month, 1);
        $this->startDayOfWeek = $this->currentDate->startOfMonth()->dayOfWeek;
        $this->daysInMonth = $this->currentDate->daysInMonth;
        $this->daysInPreviousMonth = $this->currentDate->copy()->subMonth()->daysInMonth;
    }
    public function updatedyear()
    {
        if ($this->year < 2020) {
            $this->year = 2020;
        }

        $this->reloadData();

    }

    public function updatedmonth()
    {
        $this->reloadData();
    }
    public function render()
    {
        $this->today = Carbon::now()->format('Y-m-d');
        $this->reloadData();

        return view('livewire.scheduler.calendar-list');
    }
}
