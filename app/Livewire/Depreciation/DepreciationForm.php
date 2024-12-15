<?php

namespace App\Livewire\Depreciation;

use App\Services\AccountServices;
use App\Services\DepreciationServices;
use App\Services\DocumentStatusServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Depreciation Form')]

class DepreciationForm extends Component
{
    public int $ID;
    public string $CODE;
    public string $DATE;
    public int $LOCATION_ID;
    public int $DEPRECIATION_ACCOUNT_ID;
    public string $NOTES;
    public bool $IS_AUTO;
    public float $AMOUNT;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public $accountList = [];
    public $locationList = [];

    public bool $Modify  = false;
    private $depreciationServices;
    private $userServices;
    private $locationServices;
    private $accountServices;
    private $documentStatusServices;
    public function boot(
        DepreciationServices $depreciationServices,
        UserServices $userServices,
        LocationServices $locationServices,
        AccountServices $accountServices,
        DocumentStatusServices $documentStatusServices
    ) {

        $this->depreciationServices = $depreciationServices;
        $this->userServices = $userServices;
        $this->locationServices = $locationServices;
        $this->accountServices = $accountServices;
        $this->documentStatusServices = $documentStatusServices;
    }
    public function mount($id = null)
    {
        $this->locationList = $this->locationServices->getList();
        $this->accountList = $this->accountServices->getAccount(false);
        if (is_numeric($id)) {
            $data = $this->depreciationServices->Get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            return Redirect::route('companydepreciation')->with('Record not found');
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->DEPRECIATION_ACCOUNT_ID = 0;
        $this->NOTES =  '';
        $this->IS_AUTO = false;
        $this->AMOUNT =   0;
        $this->STATUS = 0;
        $this->Modify = true;
        $this->STATUS_DESCRIPTION = "";
    }
    public function save()
    {

        $this->validate([
            'CODE'                      =>  $this->ID > 0 ? 'required|max:20|unique:depreciation,code,' . $this->ID : 'nullable',
            'DATE'                      => 'required|date',
            'LOCATION_ID'               => 'required|exists:location,id',
            'DEPRECIATION_ACCOUNT_ID'   => 'required|numeric|exists:account,id'
        ]);


        try {
            if ($this->ID  == 0) {
                $this->ID = (int) $this->depreciationServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->DEPRECIATION_ACCOUNT_ID,
                    $this->NOTES,
                    $this->IS_AUTO
                );

                return Redirect::route('companydepreciation_edit', ['id' => $this->ID]);
            }

            $this->depreciationServices->Update(
                $this->ID,
                $this->CODE,
                $this->DEPRECIATION_ACCOUNT_ID,
                $this->NOTES
            );
        } catch (\Exception $ex) {
            $errorMessage = 'Error occurred: ' . $ex->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private  function getInfo($data)
    {

        $this->ID = $data->ID ?? 0;
        $this->CODE = $data->CODE ?? '';
        $this->DATE = $data->DATE ?? '';
        $this->LOCATION_ID = $data->LOCATION_ID ?? 0;
        $this->DEPRECIATION_ACCOUNT_ID = $data->DEPRECIATION_ACCOUNT_ID ?? 0;
        $this->NOTES = $data->NOTES ?? '';
        $this->IS_AUTO = $data->IS_AUTO ?? false;
        $this->AMOUNT =  $data->AMOUNT ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    #[On('refresh-amount')]
    public function getTotal()
    {
        $data = $this->depreciationServices->Get($this->ID);
        if ($data) {
            $this->AMOUNT = $data->AMOUNT ?? 0;
            return;
        }

        $this->AMOUNT = 0;
    }
    public function render()
    {
        return view('livewire.depreciation.depreciation-form');
    }
}
