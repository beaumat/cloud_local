<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;

#[Title('Hemodialysis Treatment')]
class HemoForm extends Component
{
    public $data;
    public int $ID;
    public bool $Modify;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public $patientList = [];
    public $locationList = [];
    private $hemoServices;
    private $locationServices;
    private $contactServices;
    private $userServices;
    public int $CUSTOMER_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public string $tab = '1st';

    public function boot(HemoServices $hemoServices, ContactServices $contactServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->userServices = $userServices;

    }
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount($id = null)
    {
        $this->patientList = $this->contactServices->getList(3);
        $this->locationList = $this->locationServices->getList();
        $this->Modify = true;

        if (is_numeric($id)) {
            $data = $this->hemoServices->Get($id);
            if ($data) {
                $this->ID = $data->ID;
                $this->DATE = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->CUSTOMER_ID = $data->CUSTOMER_ID;
                $this->CODE = $data->CODE;
                $this->Modify = false;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('transactionshemo')->with('error', $errorMessage);

        }

        $this->ID = 0;
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CUSTOMER_ID = 0;
        $this->CODE = '';
    }
    public function update_all()
    {
        $this->dispatch('treatment-save');
        $this->dispatch('access-save');
        $this->dispatch('assessment-save');
        session()->flash('message', 'Successfully save');
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function updateCancel()
    {
        $data = $this->hemoServices->Get($this->ID);
        if ($data) {
            $this->DATE = $data->DATE;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->CUSTOMER_ID = $data->CUSTOMER_ID;
            $this->CODE = $data->CODE;
            $this->Modify = false;
            return;
        }


        $this->Modify = false;

    }
    public function save()
    {

        $this->validate(
            [
                'CUSTOMER_ID' => 'required|not_in:0',
                'CODE' => 'unique:hemodialysis,code,' . $this->ID,
                'DATE' => 'required',
                'LOCATION_ID' => 'required',
            ],
            [],
            [
                'CUSTOMER_ID' => 'Patient',
                'DATE' => 'Date',
                'CODE' => 'Reference No.',
                'LOCATION_ID' => 'Location'
            ]
        );


        try {

            if ($this->ID == 0) {
                $this->ID = $this->hemoServices->PreSave($this->DATE, $this->CODE, $this->CUSTOMER_ID, $this->LOCATION_ID);
                return Redirect::route('transactionshemo_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->hemoServices->PreUpdate($this->ID, $this->DATE, $this->CODE, $this->CUSTOMER_ID, $this->LOCATION_ID);
            }

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

        $this->Modify = false;

    }


    public function render()
    {
        return view('livewire.hemodialysis.hemo-form');
    }
}
