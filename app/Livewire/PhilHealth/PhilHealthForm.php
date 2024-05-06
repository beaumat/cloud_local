<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Philhealth')]

class PhilHealthForm extends Component
{

    public bool $Modify = false;
    public string $STATUS_DESCRIPTION;
    public int $STATUS;
    public int $ID = 0;
    public string $tab = "soa";
    public $patientList = [];
    public $locationList = [];
    public int $LOCATION_ID;
    public int $CONTACT_ID;
    public string $CODE;
    public $DATE;
    public $DATE_ADMITTED;
    public $TIME_ADMITTED;
    public $DATE_DISCHARGED;
    public $TIME_DISCHARGED;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public int $STATUS_ID;
    private $philHealthServices;
    private $hemoServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }

    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function UpdatedContactId()
    {

        $data = $this->hemoServices->getDateTime($this->CONTACT_ID, $this->LOCATION_ID);

        if ($data) {

            $this->DATE_ADMITTED = $data['FIRST_DATE'];
            $this->TIME_ADMITTED = $data['FIRST_TIME'];
            $this->DATE_DISCHARGED = $data['LAST_DATE'];
            $this->TIME_DISCHARGED = $data['LAST_TIME'];

            return;
        }
        $this->DATE_ADMITTED = '';
        $this->TIME_ADMITTED = '';
        $this->DATE_DISCHARGED = '';
        $this->TIME_DISCHARGED = '';


    }
    public function mount($id = null)
    {
        $this->locationList = $this->locationServices->getList();
        $this->patientList = $this->contactServices->getList(3);

        if (is_numeric($id)) {
            $data = $this->philHealthServices->get($id);

            if ($data) {
                $this->ID = $data->ID;
                $this->CODE = $data->CODE;
                $this->DATE = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->CONTACT_ID = $data->CONTACT_ID;
                $this->DATE_ADMITTED = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED = $data->TIME_DISCHARGED;
                $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS;
                $this->OTHER_DIAGNOSIS = $data->OTHER_DIAGNOSIS;
                $this->FIRST_CASE_RATE = $data->FIRST_CASE_RATE;
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE;
                $this->STATUS_ID = $data->STATUS_ID;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientsphic')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CONTACT_ID = 0;
        $this->DATE_ADMITTED = null;
        $this->TIME_ADMITTED = null;
        $this->DATE_DISCHARGED = null;
        $this->TIME_ADMITTED = null;
        $this->FINAL_DIAGNOSIS = '';
        $this->OTHER_DIAGNOSIS = '';
        $this->FIRST_CASE_RATE = '';
        $this->SECOND_CASE_RATE = '';
        $this->STATUS_ID = 0;


        $this->Modify = true;

    }

    public function updateCancel()
    {
        return Redirect::route('patientsphic_edit', ['id' => $this->ID]);
    }
    public function save()
    {

        $this->validate(
            [
                'CONTACT_ID' => 'required|not_in:0',
                'DATE' => 'required',
                'LOCATION_ID' => 'required',
                'DATE_ADMITTED' => 'required',
                'TIME_ADMITTED' => 'required',
                'DATE_DISCHARGED' => 'required',
                'TIME_DISCHARGED' => 'required'
            ],
            [],
            [
                'CONTACT_ID' => 'Patient',
                'DATE' => 'Date',
                'LOCATION_ID' => 'Location',
                'DATE_ADMITTED' => 'Date Admitted',
                'TIME_ADMITTED' => 'Time Admiited',
                'DATE_DISCHARGED' => 'Date Discharged',
                'TIME_DISCHARGED' => 'Time Discharged'
            ]
        );

        if ($this->ID == 0) {

            $this->ID = $this->philHealthServices->preSave(
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );


            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;

            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully created');

        } else {

            $this->philHealthServices->preUpdate(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_DISCHARGED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE
            );
            $this->philHealthServices->DefaultEntry($this->ID);
            $this->Modify = false;

            return Redirect::route('patientsphic_edit', ['id' => $this->ID])->with('message', 'Successfully updated');
        }

    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function print()
    {

    }
    public function render()
    {
        return view('livewire.phil-health.phil-health-form');
    }
}
