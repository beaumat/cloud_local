<?php

namespace App\Livewire\PatientReport;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title(('Annex D Report'))]
class PhilhealthAnnexTwo extends Component
{

    public $showAll = false;
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList     = [];
    public $columnList   = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    private $dateServices;

    public function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->locationServices   = $locationServices;
        $this->userServices       = $userServices;
        $this->dateServices       = $dateServices;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->YEAR        = $this->dateServices->NowYear();
        $this->MONTH       = $this->dateServices->NowMonth();

        $this->locationList = $this->locationServices->getList();
        $this->monthList    = $this->dateServices->MonthList();
    }
    public function updatedLOCATIONID()
    {

        $this->dataList   = [];


        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedYEAR()
    {

        $this->dataList   = [];
    }
    public function updatedMONTH()
    {

        $this->dataList   = [];
    }
    public function generate()
    {


        $this->dataList = $this->philHealthServices->GenerateAnnex2( $this->LOCATION_ID, $this->showAll);

    }


    public function render()
    {
        return view('livewire.patient-report.philhealth-annex-two');
    }
}
