<?php

namespace App\Livewire\PatientReport;

use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\PatientReportServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Patient Inventory Report')]
class PatientInventoryReport extends Component
{

    public bool $refreshComponent = false;
    public $DATE_FROM;
    public $DATE_TO;
    public int $LOCATION_ID;
    public int $ITEM_ID = 0;
    public $locationList = [];
    public $dataList = [];
    public $itemList = [];   
    private $patientReportServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $itemServices;
    public function boot(
        PatientReportServices $patientReportServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        ItemServices $itemServices
    ) {
        $this->patientReportServices = $patientReportServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->itemServices = $itemServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
        $this->itemList = $this->itemServices->getInventoryItem( false);
    }
    public function generate()
    {

        $this->dataList = $this->patientReportServices->getInventoryList(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID,
            $this->ITEM_ID
        );
    }
    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function render()
    {
        return view('livewire.patient-report.patient-inventory-report');
    }
}
