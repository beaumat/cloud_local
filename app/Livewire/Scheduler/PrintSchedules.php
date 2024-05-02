<?php

namespace App\Livewire\Scheduler;

use App\Services\DateServices;
use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintSchedules extends Component
{
    #[Reactive]
    public int $YEAR;
    #[Reactive]
    public int $MONTH;
    #[Reactive]
    public int $LOCATION_ID;

    public bool $showModal = false;
    public $shiftList = [];
    public string $DATE_START;
    public string $DATE_END;
    public int $SHIFT_ID = 0;
    public int $WEEKLY_ID;
    public $weekdays = [];
    public $weekLevels = [];
    private $shiftServices;
    private $dateServices;
    public function boot(ShiftServices $shiftServices, DateServices $dateServices)
    {
        $this->shiftServices = $shiftServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->WEEKLY_ID = 1;
        $this->shiftList = $this->shiftServices->List();
        $this->weekLevels = $this->dateServices->WeeklyLevel();
    }
    public function updatedweeklyId()
    {
        $this->reloadWeekly();
    }

    private function reloadWeekly()
    {
        // $this->weekdays = $this->dateServices->Get7Days($this->YEAR, $this->MONTH, $this->WEEKLY_ID);
    }

    #[On('print-modal')]
    public function openModal()
    {

        $this->showModal = true;
    }
    #[On('schedule-modal-close')]
    public function closeModal()
    {
        $this->showModal = false;
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
        return view('livewire.scheduler.print-schedules');
    }
}
