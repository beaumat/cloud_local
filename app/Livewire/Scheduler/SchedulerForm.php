<?php

namespace App\Livewire\Scheduler;


use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\ScheduleServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Schedules')]
class SchedulerForm extends Component
{
    public $month;
    public $year;
    public $contactList = [];
    public $refreshComponent = false;
    protected $listeners = ['reloadComponent'];
    public $CONTACT_ID;
    public $LOCATION_ID;
    public $HEMO_MACHINE_ID;
    public $locationList = [];
    private $locationServices;
    private $contactServices;
    private $userServices;
    private $dateServices;
    private $scheduleServices;
    public $monthList = [];
    public $scheduleList = [];

    public function boot(
        LocationServices $locationServices,
        ContactServices $contactServices,
        UserServices $userServices,
        DateServices $dateServices,
        ScheduleServices $scheduleServices
    ) {
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->scheduleServices = $scheduleServices;
    }
    #[On('load-schedule-by-contact')]
    public function loadScheduleByContact()
    {
        $this->scheduleList = $this->scheduleServices->ContactSchedule($this->CONTACT_ID ?? 0, $this->LOCATION_ID ?? 0);
    }
    public function updatedcontactid()
    {
        $this->reloadComponent();
        $this->loadScheduleByContact();
        $data = $this->contactServices->get($this->CONTACT_ID, 3);
        if ($data) {
            $this->HEMO_MACHINE_ID = $data->PATIENT_TYPE_ID;
            return;
        }
        $this->HEMO_MACHINE_ID = 0;
    }
    public function updatedlocationid()
    {
        $this->reloadComponent();
        $this->loadScheduleByContact();
    }
    public function reloadComponent()
    {
        $this->refreshComponent = !$this->refreshComponent;
    }
    public function updatedyear()
    {
        if ($this->year < 2020) {
            $this->year = 2020;
        }
        $this->reloadComponent();
    }
    public function updatedmonth()
    {
        $this->reloadComponent();
    }
    private function resetDate()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->monthList = $this->dateServices->MonthList();
        $this->resetDate();
    }
    public function todayMonth()
    {
        $this->resetDate();
        $this->reloadComponent();
    }
    public function nextMonth()
    {
        $this->month = $this->month == 12 ? 1 : $this->month + 1;
        $this->year = $this->month == 1 ? $this->year + 1 : $this->year;
        $this->reloadComponent();
    }
    public function previousMonth()
    {
        $this->month = $this->month == 1 ? 12 : $this->month - 1;
        $this->year = $this->month == 12 ? $this->year - 1 : $this->year;
        $this->reloadComponent();
    }

    public function render()
    {

        $this->contactList = $this->contactServices->getList(3);
        $this->locationList = $this->locationServices->getList();
        $this->loadScheduleByContact();
        return view('livewire.scheduler.scheduler-form');
    }

}

