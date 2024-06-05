<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Livewire\WithFileUploads;

#[Title('Hemodialysis Treatment')]
class HemoForm extends Component
{
    use WithFileUploads;
    public string $FILE_NAME;
    public string $FILE_PATH;
    public $PDF = null;
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
    public string $PRE_WEIGHT;
    public string $PRE_BLOOD_PRESSURE;
    public string $PRE_BLOOD_PRESSURE2;
    public string $PRE_HEART_RATE;
    public string $PRE_O2_SATURATION;
    public string $PRE_TEMPERATURE;
    public string $POST_WEIGHT;
    public string $POST_BLOOD_PRESSURE;
    public string $POST_BLOOD_PRESSURE2;
    public string $POST_HEART_RATE;
    public string $POST_O2_SATURATION;
    public string $POST_TEMPERATURE;
    public string $TIME_START;
    public string $TIME_END;

    // old

    public string $OLD_PRE_WEIGHT;
    public string $OLD_PRE_BLOOD_PRESSURE;
    public string $OLD_PRE_BLOOD_PRESSURE2;
    public string $OLD_PRE_HEART_RATE;
    public string $OLD_PRE_O2_SATURATION;
    public string $OLD_PRE_TEMPERATURE;
    public string $OLD_POST_WEIGHT;
    public string $OLD_POST_BLOOD_PRESSURE;
    public string $OLD_POST_BLOOD_PRESSURE2;
    public string $OLD_POST_HEART_RATE;
    public string $OLD_POST_O2_SATURATION;
    public string $OLD_POST_TEMPERATURE;


    // end old


    private $uploadServices;

    public function boot(
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        UploadServices $uploadServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
    }

    public function reloadData($data)
    {
        $this->ID = $data->ID;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->CUSTOMER_ID = $data->CUSTOMER_ID;
        $this->CODE = $data->CODE;
        $this->Modify = false;

        $this->PRE_WEIGHT = $data->PRE_WEIGHT ?? "";
        $this->PRE_BLOOD_PRESSURE = $data->PRE_BLOOD_PRESSURE ?? "";
        $this->PRE_BLOOD_PRESSURE2 = $data->PRE_BLOOD_PRESSURE2 ?? "";
        $this->PRE_HEART_RATE = $data->PRE_HEART_RATE ?? "";
        $this->PRE_O2_SATURATION = $data->PRE_O2_SATURATION ?? "";
        $this->PRE_TEMPERATURE = $data->PRE_TEMPERATURE ?? "";
        $this->POST_WEIGHT = $data->POST_WEIGHT ?? "";
        $this->POST_BLOOD_PRESSURE = $data->POST_BLOOD_PRESSURE ?? "";
        $this->POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
        $this->POST_HEART_RATE = $data->POST_HEART_RATE ?? "";
        $this->POST_O2_SATURATION = $data->POST_O2_SATURATION ?? "";
        $this->POST_TEMPERATURE = $data->POST_TEMPERATURE ?? "";

        $this->TIME_START = $data->TIME_START ?? "";
        $this->TIME_END = $data->TIME_END ?? "";

        $this->FILE_NAME = $data->FILE_NAME ?? "";
        $this->FILE_PATH = $data->FILE_PATH ?? "";
        $this->STATUS = $data->STATUS ?? 0;
        $this->getPreviousTreatment();
    }
    public function mount($id = null)
    {
        $this->patientList = $this->contactServices->getList(3);
        $this->locationList = $this->locationServices->getList();
        $this->Modify = true;

        if (is_numeric($id)) {
            $data = $this->hemoServices->Get($id);
            if ($data) {
                $this->reloadData($data);
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('patientshemo')->with('error', $errorMessage);

        }

        $this->ID = 0;
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CUSTOMER_ID = 0;
        $this->CODE = '';

        $this->PRE_WEIGHT = "";
        $this->PRE_BLOOD_PRESSURE = "";
        $this->PRE_BLOOD_PRESSURE2 = "";
        $this->PRE_HEART_RATE = "";
        $this->PRE_O2_SATURATION = "";
        $this->PRE_TEMPERATURE = "";
        $this->POST_WEIGHT = "";
        $this->POST_BLOOD_PRESSURE = "";
        $this->POST_BLOOD_PRESSURE2 = "";
        $this->POST_HEART_RATE = "";
        $this->POST_O2_SATURATION = "";
        $this->POST_TEMPERATURE = "";

        $this->PDF = null;
        $this->FILE_NAME = '';
        $this->FILE_PATH = '';
        $this->STATUS  = 0;

    }
    public function getPreviousTreatment()
    {

        $data = $this->hemoServices->GetLastTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);

        if ($data) {
            $this->OLD_PRE_WEIGHT = $data->PRE_WEIGHT ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE = $data->PRE_BLOOD_PRESSURE ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE2 = $data->PRE_BLOOD_PRESSURE2 ?? "";
            $this->OLD_PRE_HEART_RATE = $data->PRE_HEART_RATE ?? "";
            $this->OLD_PRE_O2_SATURATION = $data->PRE_O2_SATURATION ?? "";
            $this->OLD_PRE_TEMPERATURE = $data->PRE_TEMPERATURE ?? "";
            $this->OLD_POST_WEIGHT = $data->POST_WEIGHT ?? "";
            $this->OLD_POST_BLOOD_PRESSURE = $data->POST_BLOOD_PRESSURE ?? "";
            $this->OLD_POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
            $this->OLD_POST_HEART_RATE = $data->POST_HEART_RATE ?? "";
            $this->OLD_POST_O2_SATURATION = $data->POST_O2_SATURATION ?? "";
            $this->OLD_POST_TEMPERATURE = $data->POST_TEMPERATURE ?? "";
            return;
        }

        $this->OLD_PRE_WEIGHT = "";
        $this->OLD_PRE_BLOOD_PRESSURE = "";
        $this->OLD_PRE_BLOOD_PRESSURE2 = "";
        $this->OLD_PRE_HEART_RATE = "";
        $this->OLD_PRE_O2_SATURATION = "";
        $this->OLD_PRE_TEMPERATURE = "";
        $this->OLD_POST_WEIGHT = "";
        $this->OLD_POST_BLOOD_PRESSURE = "";
        $this->OLD_POST_BLOOD_PRESSURE2 = "";
        $this->OLD_POST_HEART_RATE = "";
        $this->OLD_POST_O2_SATURATION = "";
        $this->OLD_POST_TEMPERATURE = "";
    }
    public function update_all()
    {

        $this->hemoServices->Update(
            $this->ID,
            $this->PRE_WEIGHT,
            $this->PRE_BLOOD_PRESSURE,
            $this->PRE_BLOOD_PRESSURE2,
            $this->PRE_HEART_RATE,
            $this->PRE_O2_SATURATION,
            $this->PRE_TEMPERATURE,
            $this->POST_WEIGHT,
            $this->POST_BLOOD_PRESSURE,
            $this->POST_BLOOD_PRESSURE2,
            $this->POST_HEART_RATE,
            $this->POST_O2_SATURATION,
            $this->POST_TEMPERATURE,
            $this->TIME_START,
            $this->TIME_END
        );

        if ($this->PDF != null) {

            $this->uploadServices->RemoveIfExists($this->FILE_PATH);

            $returnData = $this->uploadServices->Treatment($this->PDF);

            $this->hemoServices->UpdateFile($this->ID, $returnData['filename'] . '.' . $returnData['extension'], $returnData['new_path']);
        }

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
            $this->reloadData($data);
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
                return Redirect::route('patientshemo_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->hemoServices->PreUpdate($this->ID, $this->DATE, $this->CODE, $this->CUSTOMER_ID, $this->LOCATION_ID);
            }

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

        $this->Modify = false;

    }
    public function updatedPdf()
    {
        $this->validate([
            'PDF' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }

    public function render()
    {
        return view('livewire.hemodialysis.hemo-form');
    }
}
