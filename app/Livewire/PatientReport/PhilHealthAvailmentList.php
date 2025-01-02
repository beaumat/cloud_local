<?php

namespace App\Livewire\PatientReport;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Philhealth Availment List')]
class PhilHealthAvailmentList extends Component
{
    public $search;
    public $LOCATION_ID;
    public $locationList = [];
    public $YEAR;
    public $yearList = [];
    public $patientList = [];
    public bool $SelectAll = false;
    public string $ids;
    public $selectPatient = [];
    private $contactServices;
    private $locationServices;
    private $dateServices;
    private $userServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices,
        DateServices $dateServices,
        UserServices $userServices
    ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->dateServices = $dateServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->YEAR = $this->dateServices->NowYear();
        $this->locationList = $this->locationServices->getList();
        $this->yearList = $this->dateServices->YearList();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->patientList as $list) {
                $this->selectPatient[$list->ID] = true;
            }
        } else {

            $this->reset('selectPatient');
        }
    }
    public function printAll()
    {

        $this->ids = "";
        foreach ($this->selectPatient as $pid => $isSelect) {
            if ($isSelect) {
                if ($this->ids == "") {
                    $this->ids = $pid;
                } else {
                    $this->ids = $this->ids . "," . $pid;
                }
            }
        }

        if ($this->ids == "") {
            return;
        }

        $url = route('reportsphilhealth_availment_list_print', ['id' => $this->ids, 'locationid' => $this->LOCATION_ID, 'year' => $this->YEAR]);

        $this->dispatch('OpenNewTab', data: $url);
    }
    public function render()
    {

        $this->patientList = $this->contactServices->getPatientAvailmentList($this->search, $this->LOCATION_ID, $this->YEAR);

        return view('livewire.patient-report.phil-health-availment-list');
    }
}
