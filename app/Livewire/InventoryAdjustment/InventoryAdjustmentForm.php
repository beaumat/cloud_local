<?php

namespace App\Livewire\InventoryAdjustment;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\DocumentStatusServices;
use App\Services\InventoryAdjustmentServices;
use App\Services\InventoryAdjustmentTypeServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Inventory Adjustment')]
class InventoryAdjustmentForm extends Component
{
    public int $openStatus = 0;
    public int $ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $ADJUSTMENT_TYPE_ID;
    public string $NOTES;
    public int $ACCOUNT_ID;
    public $locationList = [];
    public $adjustmentTypeList = [];
    public bool $Modify;
    public bool $transferReset = false;
    private $inventoryAdjustmentServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    private $documentStatusServices;
    private $contactServices;
    private $inventoryAdjustmentTypeServices;

    public function boot(
        InventoryAdjustmentServices $inventoryAdjustmentServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        DocumentStatusServices $documentStatusServices,
        InventoryAdjustmentTypeServices $inventoryAdjustmentTypeServices
    ) {
        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->documentStatusServices = $documentStatusServices;
        $this->inventoryAdjustmentTypeServices = $inventoryAdjustmentTypeServices;
    }
    public function LoadDropdown()
    {
        $this->locationList = $this->locationServices->getList();
        $this->adjustmentTypeList = $this->inventoryAdjustmentTypeServices->getList();
    }
    public function posted()
    {
        try {
            $count = (float) $this->inventoryAdjustmentServices->CountItems($this->ID);
            if ($count == 0) {
                Session()->flash('error', 'No item to adjust');
                return;
            }
            $this->inventoryAdjustmentServices->StatusUpdate($this->ID, 15);
            $this->STATUS = 15;
            Session()->flash('message', 'Successfully posted');
        } catch (\Exception $e) {
            Session()->flash('error', $e->getMessage());
        }
    }
    private function getInfo($data)
    {
        $this->ID = $data->ID;
        $this->CODE = $data->CODE;
        $this->DATE = $data->DATE;
        $this->LOCATION_ID = $data->LOCATION_ID;
        $this->NOTES = $data->NOTES ?? '';
        $this->ADJUSTMENT_TYPE_ID = $data->ADJUSTMENT_TYPE_ID ?? 0;
        $this->ACCOUNT_ID = $data->ACCOUNT_ID ?? 0;
        $this->STATUS = $data->STATUS ?? 0;
        $this->STATUS_DESCRIPTION = $this->documentStatusServices->getDesc($this->STATUS);
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();
        if (is_numeric($id)) {
            $data = $this->inventoryAdjustmentServices->Get($id);
            if ($data) {
                $this->getInfo($data);
                $this->Modify = false;
                return;
            }
            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('companyinventory_adjustment')->with('error', $errorMessage);
        }

        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->ADJUSTMENT_TYPE_ID = 0;
        $this->NOTES = '';
        $this->ACCOUNT_ID = 0;
        $this->STATUS = 0;
        $this->STATUS_DESCRIPTION = '';
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save()
    {
        try {

            if ($this->ID == 0) {

                $this->validate(
                    [

                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'ADJUSTMENT_TYPE_ID' => 'required|not_in:0'

                    ],
                    [],
                    [

                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'ADJUSTMENT_TYPE_ID' => 'Adjustment Type'

                    ]
                );

                $this->ACCOUNT_ID = $this->inventoryAdjustmentTypeServices->getAccountId($this->ADJUSTMENT_TYPE_ID);

                if ($this->ACCOUNT_ID == 0) {
                    session()->flash('error', 'Adjustment type account not found.');
                    return;
                }

                \DB::beginTransaction();

                $this->ID = $this->inventoryAdjustmentServices->Store(
                    $this->CODE,
                    $this->DATE,
                    $this->LOCATION_ID,
                    $this->ADJUSTMENT_TYPE_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES
                );
                
                \DB::commit();
                return Redirect::route('companyinventory_adjustment_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {
                $this->validate(
                    [

                        'CODE' => 'required|max:20|unique:stock_transfer,code,' . $this->ID,
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'ADJUSTMENT_TYPE_ID' => 'required|not_in:0'

                    ],
                    [],
                    [
                        'CODE' => 'Reference No.',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'ADJUSTMENT_TYPE_ID' => 'Adjustment Type'
                    ]
                );

                $this->ACCOUNT_ID = $this->inventoryAdjustmentTypeServices->getAccountId($this->ADJUSTMENT_TYPE_ID);

                if ($this->ACCOUNT_ID == 0) {
                    session()->flash('error', 'Adjustment type account not found.');
                    return;
                }


                \DB::beginTransaction();
                $this->inventoryAdjustmentServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->LOCATION_ID,
                    $this->ADJUSTMENT_TYPE_ID,
                    $this->ACCOUNT_ID,
                    $this->NOTES,

                );
                \DB::commit();
                session()->flash('message', 'Successfully updated');
            }
            $this->updateCancel();
        } catch (\Exception $e) {
            \DB::rollback();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function updateCancel()
    {
        $BA = $this->inventoryAdjustmentServices->get($this->ID);
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
        return view('livewire.inventory-adjustment.inventory-adjustment-form');
    }
}
