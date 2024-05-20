<?php

namespace App\Livewire\BuildAssembly;

use App\Services\BuildAssemblyServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\SystemSettingServices;
use App\Services\UnitOfMeasureServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Build Assembly')]
class BuildAssemblyForm extends Component
{
    public int $ID;
    public int $ASSEMBLY_ITEM_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public string $NOTES;
    public int $ASSET_ACCOUNT_ID;
    public float $QUANTITY;
    public float $AMOUNT;
    public int $BATCH_ID;
    public int $UNIT_ID;
    public int $UNIT_BASE_QUANTITY;
    public int $STATUS;
    public $itemList = [];
    public $locationList = [];
    public $unitList = [];
    public bool $Modify;
    private $buildAssemblyServices;
    private $locationServices;
    private $itemServices;
    private $userServices;
    private $documentStatusServices;
    private $systemSettingServices;
    private $dateServices;
    private $unitOfMeasureServices;
    public function boot(
        BuildAssemblyServices $buildAssemblyServices,
        LocationServices $locationServices,
        ItemServices $itemServices,
        UserServices $userServices,
        DocumentStatusServices $documentStatusServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        UnitOfMeasureServices $unitOfMeasureServices

    ) {
        $this->buildAssemblyServices = $buildAssemblyServices;
        $this->locationServices = $locationServices;
        $this->itemServices = $itemServices;
        $this->userServices = $userServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;

    }
    public function LoadDropdown()
    {
        $this->itemList = $this->itemServices->AssemblyItem();
        $this->locationList = $this->locationServices->getList();
        $this->unitList = [];
    }

    public function updatedASSEMBLYITEMID()
    {
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ASSEMBLY_ITEM_ID);
    }

    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->DATE_EXPECTED = $data->DATE_EXPECTED ? $data->DATE_EXPECTED : '';
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->ASSEMBLY_ITEM_ID = $data->ASSEMBLY_ITEM_ID;
        $this->ASSET_ACCOUNT_ID = $data->ASSET_ACCOUNT_ID ? $data->ASSET_ACCOUNT_ID : 0;
        $this->updatedUnitId();
        $this->NOTES = $data->NOTES ?? '';
        $this->AMOUNT = $data->AMOUNT;
        $this->QUANTITY = $data->QUANTITY;
        $this->BATCH_ID = $data->BITCH_ID;
        $this->UNIT_ID = $data->UNIT_ID;
        $this->UNIT_BASE_QUANTITY = $data->UNIT_BASE_QUANTITY;
        $this->STATUS = $data->STATUS;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();

        if (is_numeric($id)) {
            $PO = $this->buildAssemblyServices->get($id);
            if ($PO) {
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companybuild_assembly')->with('error', $errorMessage);
        }

        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->ASSEMBLY_ITEM_ID = 0;
        $this->ASSET_ACCOUNT_ID = 21;
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->QUANTITY = 0;
        $this->BATCH_ID = 0;
        $this->UNIT_ID = 0;
        $this->UNIT_BASE_QUANTITY = 1;
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = "";
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {
        try {

            $this->validate(
                [
                    'ASSEMBLY_ITEM_ID' => 'required|not_in:0',
                    'CODE' => 'required|max:20|unique:build_assembly,code,' . $this->ID,
                    'DATE' => 'required',
                    'LOCATION_ID' => 'required',
                    'QUANTITY' => 'required|not_in:0',
                ],
                [],
                [
                    'ASSEMBLY_ITEM_ID' => 'Aseembly Item',
                    'CODE' => 'Reference No.',
                    'DATE' => 'Date',
                    'LOCATION_ID' => 'Location',
                    'QUANTITY' => 'Quantity'
                ]
            );
            if ($this->ID == 0) {
                $this->ID = $this->buildAssemblyServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->ASSEMBLY_ITEM_ID,
                    $this->QUANTITY,
                    $this->BATCH_ID,
                    $this->UNIT_ID,
                    $this->UNIT_BASE_QUANTITY,
                    $this->NOTES,
                    $this->ASSET_ACCOUNT_ID
                );

                return Redirect::route('companybuild_assembly_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {
                $this->buildAssemblyServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->QUANTITY,
                    $this->BATCH_ID,
                    $this->UNIT_ID,
                    $this->UNIT_BASE_QUANTITY,
                    $this->NOTES
                );
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updateCancel()
    {
        $BA = $this->buildAssemblyServices->get($this->ID);
        if ($BA) {
            $this->getInfo($BA);
        }
        $this->Modify = false;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.build-assembly.build-assembly-form');
    }
}
