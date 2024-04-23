<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Component;

class QuickCreate extends Component
{
    public $patientSelected = [];
    public bool $SelectAll = false;
    public $locationList = [];
    public int $LOCATION_ID;
    public bool $showModal = false;
    private $locationServices;
    private $userServices;
    public $DATE_FROM;
    public $DATE_TO;
    public $dataList = [];
    private $hemoServices;
    private $philHealthServices;
    private $contactServices;
    public function boot(LocationServices $locationServices, UserServices $userServices, HemoServices $hemoServices, PhilHealthServices $philHealthServices, ContactServices $contactServices)
    {
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->hemoServices = $hemoServices;
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
    }
    public function ResetValue()
    {
        $this->SelectAll = false;
        $this->patientSelected = [];
    }
    public function updatedDateFrom()
    {
        $this->ResetValue();
    }
    public function updatedDateTo()
    {
        $this->ResetValue();
    }
    public function updatedLocationId()
    {
        $this->ResetValue();
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->DATE_FROM = Carbon::now()->format('Y-m-d');
        $this->DATE_TO = Carbon::now()->format('Y-m-d');
    }
    public function updatedSelectAll($value)
    {

        if ($value) {
            foreach ($this->dataList as $list) {
                $this->patientSelected[$list->ID] = true;
            }
        } else {
            $this->patientSelected = [];
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    private $DATE_ADMITTED = '';
    private $TIME_ADMITTED = '';
    private $DATE_DISCHARGED = '';
    private $TIME_DISCHARGED = '';

    private string $FINAL_DIAGNOSIS = '';
    private string $OTHER_DIAGNOSIS = '';
    private string $FIRST_CASE_RATE = '';
    private string $SECOND_CASE_RATE = '';

    private function generateDateTime($CONTACT_ID): bool
    {
        $data = $this->hemoServices->getDateTime($CONTACT_ID, $this->LOCATION_ID);
        if ($data) {
            $this->DATE_ADMITTED = $data['FIRST_DATE'];
            $this->TIME_ADMITTED = $data['FIRST_TIME'];
            $this->DATE_DISCHARGED = $data['LAST_DATE'];
            $this->TIME_DISCHARGED = $data['LAST_TIME'];
            return true;
        }

        return false;
    }
    public function generateRemarks($CONTACT_ID)
    {
        $contact = $this->contactServices->get($CONTACT_ID, 3);

        if ($contact) {
            $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
            $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
            $this->FIRST_CASE_RATE = $contact->FIRST_CASE_RATE ?? '';
            $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
        }
    }
    public function create()
    {

        $gotSelected = false;


        foreach ($this->patientSelected as $patientID => $isSelected) {
            if ($isSelected) {
                $gotSelected = true;
                if ($this->generateDateTime($patientID)) {
                    $this->generateRemarks($patientID);
                    $ID  = (int) $this->philHealthServices->preSave(
                        '',
                        Carbon::now()->format('Y-m-d'),
                        $this->LOCATION_ID,
                        $patientID,
                        $this->DATE_ADMITTED,
                        $this->TIME_ADMITTED,
                        $this->DATE_DISCHARGED,
                        $this->TIME_DISCHARGED,
                        $this->FINAL_DIAGNOSIS,
                        $this->OTHER_DIAGNOSIS,
                        $this->FIRST_CASE_RATE,
                        $this->SECOND_CASE_RATE
                    );
                    $this->philHealthServices->DefaultEntry($ID);
                }
            }
        }

        if ($gotSelected) {
            $this->dispatch('reload-list');
            $this->ResetValue();
            $this->showModal = false;
       
        }
    }
    public function render()
    {

        $this->dataList = $this->hemoServices->QuickFilterByDateRange($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        return view('livewire.phil-health.quick-create');
    }
}
