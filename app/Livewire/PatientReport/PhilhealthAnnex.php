<?php
namespace App\Livewire\PatientReport;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title(('Annex C Report'))]
class PhilhealthAnnex extends Component
{

    public int $YEAR;
    public int $MONTH;
    public $monthList = [];
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
        $this->columnType = 0;
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
        $this->columnType = 0;
        $this->dataList   = [];
    }
    public function updatedMONTH()
    {
        $this->columnType = 0;
        $this->dataList   = [];
    }
    public function generate()
    {
        $this->dataList = $this->philHealthServices->GenerateAnnex($this->YEAR, $this->MONTH, $this->LOCATION_ID);
    }

    private int $autoNumber = 0;
    private function setData($data)
    {
        $this->autoNumber++;

        $ref     = strtoupper(date('M', mktime(0, 0, 0, $this->MONTH, 1))) . substr((string) $this->YEAR, -2) . str_pad($this->autoNumber, 3, '0', STR_PAD_LEFT); // Generate a reference number based on the month and year
        $isExist = $this->philHealthServices->ifClaimNoExists($data->ID, $ref);
        if (! $isExist) {
            // if claim number does not exist, update it
            $this->philHealthServices->updateClaimNo($data->ID, $ref);
        } else {
            // try again with a different reference number
            $this->setData($data);
        }

    }
    public function autoSet()
    {
        $this->autoNumber == 0;
        // Reset autoNumber to 0 before generating new data
        if ($this->columnType == 1) {
            $dataList = $this->philHealthServices->GenerateAnnex($this->YEAR, $this->MONTH, $this->LOCATION_ID);
            foreach ($dataList as $data) {
                $this->setData($data);
            }
            $this->generate();
        }

    }

    public function render()
    {
        return view('livewire.patient-report.philhealth-annex');
    }
}
