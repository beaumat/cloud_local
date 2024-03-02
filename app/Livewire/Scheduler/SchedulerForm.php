<?php

namespace App\Livewire\Scheduler;

use App\Models\Contacts;
use App\Models\Shift;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SchedulerForm extends Component
{
    public $month;
    public $year;
    public $contactList = [];
    public $refreshComponent = false;
    protected $listeners = ['reloadComponent'];
    public $CONTACT_ID;

    public $LOCATION_ID;
    public $locationList = [];
    private $locationServices;
    private $contactServices;
    private $userServices;
    private $dateServices;
    public $monthList = [];
    public function boot(LocationServices $locationServices, ContactServices $contactServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function updatedcontactid()
    {
        $this->reloadComponent();
    }
    public function updatedlocationid()
    {
        $this->reloadComponent();
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
    public function mount()
    {
        $this->contactList = $this->contactServices->getList(3);

        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->monthList = $this->dateServices->MonthList();
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
    }
    public function todayMonth()
    {
        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
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

        return view('livewire.scheduler.scheduler-form');
    }

}

