<?php

namespace App\Livewire\DashboardPage;

use App\Services\PatientStatusServices;
use Livewire\Component;

class PayableStatus extends Component
{
    public $locationList = [];
    public bool $isShow = false;
    private $patientStatusServices;
    public function boot(PatientStatusServices $patientStatusServices)
    {
        $this->patientStatusServices = $patientStatusServices;
    }

    public function onClickWid()
    {
        $this->isShow = $this->isShow ? false : true;
    }
    public function render()
    {

        if ($this->isShow) {
            $this->locationList = $this->patientStatusServices->getPayableAging();
        } else {
            $this->locationList = [];
        }
        return view('livewire.dashboard-page.payable-status');
    }
}
