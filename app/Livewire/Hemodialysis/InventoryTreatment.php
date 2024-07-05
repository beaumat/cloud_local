<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\ItemTreatmentServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InventoryTreatment extends Component
{
    #[Reactive]
    public bool $ActiveRequired;
    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $LOCATION_ID;
    public int $openStatus = 1; // draft default
    public bool $saveSuccess = false;
    public $dataList = [];
    private $hemoServices;
    private $itemServices;
    private $unitOfMeasureServices;
    private $itemTreatmentServices;
    public function boot(
        HemoServices $hemoServices,
        ItemServices $itemServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemTreatmentServices $itemTreatmentServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->itemServices = $itemServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
    }

    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public bool $codeBase;
    public $itemCodeList = [];
    public $itemDescList = [];
    public int $ITEM_ID = 0;
    public float $QUANTITY;
    public int $UNIT_ID = 0;
    public $unitList = [];
    public $editUnitList = [];
    public bool $IS_NEW;

    public $ItemRequiredList = [];
    public function mount()
    {
        $this->codeBase = false;
        $this->updatedcodeBase();
    }
    public function updatedItemId()
    {
        $this->UNIT_ID = 0;

        $this->QUANTITY = 1;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->unitList = [];
        $this->IS_NEW = true;
        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;
                $this->UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 1;
            }
        }
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemTreatmentServices->getItemList(true, $this->LOCATION_ID);
            return;
        }
        $this->itemDescList = $this->itemTreatmentServices->getItemList(false, $this->LOCATION_ID);
    }
    public function deleteItem(int $ID, int $ITEM_ID)
    {
        $this->hemoServices->ItemDelete($ID, $this->HEMO_ID, $ITEM_ID);
        session()->flash('message', 'Successfully deleted');
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0',
                'QUANTITY' => 'required|not_in:0',
            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'QUANTITY' => 'Quantity'
            ]
        );
        $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);
        $this->hemoServices->ItemStore($this->HEMO_ID, $this->ITEM_ID, $this->QUANTITY, $this->UNIT_ID, (float) $unitRelated['QUANTITY'], $this->IS_NEW);
        $this->resetInsert();
        session()->flash('message', 'Successfully added');
    }
    private function resetInsert()
    {

        $this->ITEM_ID = 0;
        $this->QUANTITY = 0;
        $this->UNIT_ID = 0;
        $this->IS_NEW = false;
        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->updatedcodeBase();
        $this->saveSuccess = $this->saveSuccess ? false : true;
    }
    public $lineId = null;
    public int $lineItemId = 0;
    public float $lineQty;
    public int $lineUnitId;
    public bool $lineIsNew;

    public function editItem(int $editId)
    {

        $data = $this->hemoServices->ItemGet($editId);
        if ($data) {
            $this->lineId = $data->ID;
            $this->lineItemId = $data->ITEM_ID;
            $this->lineUnitId = $data->UNIT_ID ?? 0;
            $this->lineQty = $data->QUANTITY ?? 0;
            $this->lineIsNew = $data->IS_NEW;
            if ($this->lineItemId > 0) {
                $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
            }
        }
    }

    public function cancelItem()
    {
        $this->lineId = null;
        $this->lineItemId = 0;
        $this->lineUnitId = 0;
        $this->lineQty = 0;
        $this->lineIsNew = false;
    }

    public function updateItem()
    {

        $this->validate(
            [
                'lineQty' => 'required|not_in:0',
            ],
            [],
            [
                'lineQty' => 'Quantity'
            ]
        );

        $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);

        $this->hemoServices->ItemUpdate(
            $this->lineId,
            $this->HEMO_ID,
            $this->lineItemId,
            $this->lineQty,
            $this->lineUnitId,
            (float)  $unitRelated['QUANTITY'],
            $this->lineIsNew,
        );

        session()->flash('message', 'Successfully updated');
        $this->cancelItem();
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function loadItemRequired()
    {
        if ($this->ActiveRequired) {

            if ($this->itemTreatmentServices->getRequiredSuccess($this->LOCATION_ID, $this->HEMO_ID)) {

                $this->ItemRequiredList =  [];
                return;
            }

            $this->ItemRequiredList =  $this->itemTreatmentServices->getItemRequired($this->LOCATION_ID, $this->HEMO_ID);
        }
    }
    public function addItem(int $ItemTreatmentId)
    {
        $data = $this->itemTreatmentServices->Get($ItemTreatmentId);

        if ($data) {
            $gotNew = true;
            if ($data->NO_OF_USED > 1) {
                $hemoData =  $this->hemoServices->Get($this->HEMO_ID);
                if ($hemoData) {
                    $totalused = (int)  $this->hemoServices->getItemTotalUsed($data->ITEM_ID, $this->LOCATION_ID, $hemoData->CUSTOMER_ID, $hemoData->DATE);
                    if ($totalused == 0) {
                        $gotNew = true;
                    } elseif ($totalused < $data->NO_OF_USED) {
                        $gotNew = false;
                    }
                }
            }
            try {
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
                $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
                $this->hemoServices->ItemStore($this->HEMO_ID, $data->ITEM_ID, $data->QUANTITY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew);
                $this->dispatch('refresh-item-treatment');
            } catch (\Throwable $th) {

                session()->flash('error', $th->getMessage());
            }
        }
    }
    #[On('refresh-item-treatment')]
    public function render()
    {
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->dataList = $this->hemoServices->ItemView($this->HEMO_ID);
        $this->loadItemRequired();
        return view('livewire.hemodialysis.inventory-treatment');
    }

    public function OpenUsageHistory(int $ITEM_ID)
    {
        $data = $this->hemoServices->get($this->HEMO_ID);
        if ($data) {
            $result = [
                'DATE' => $data->DATE,
                'LOCATION_ID' => $data->LOCATION_ID,
                'ITEM_ID' => $ITEM_ID,
                'CONTACT_ID' => $data->CUSTOMER_ID
            ];

            $this->dispatch('usage-modal-open', result: $result);
        }
    }
}
