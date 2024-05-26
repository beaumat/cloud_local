<?php

namespace App\Livewire\InventoryAdjustment;

use App\Services\InventoryAdjustmentServices;
use App\Services\ItemServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InventoryAdjustmentFormItems extends Component
{

    #[Reactive()]
    public int $INVENTORY_ADJUSTMENT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $openStatus;
    public int $ID;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public float $UNIT_COST;
    public int $UNIT_ID;
    public float $UNIT_BASE_QUANTITY;
    public int $ACCOUNT_ID;
    public float $ASSET_ACCOUNT_ID;

    public int $BATCH_ID;
    public $itemList = [];
    public $editItemId = null;
    public bool $codeBase = false;
    public $itemDescList = [];
    public $itemCodeList = [];
    public $unitList = [];
    public $saveSuccess;
    public float $lineQty;
    public int $lineUnitId;
    public int $lineBatchId;
    public $editUnitList = [];
    public int $lineItemId = 0;
    private $inventoryAdjustmentServices;
    private $unitOfMeasureServices;
    private $itemServices;
    public function boot(
        InventoryAdjustmentServices $inventoryAdjustmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemServices $itemServices,
    ) {
        $this->inventoryAdjustmentServices = $inventoryAdjustmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemServices = $itemServices;
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getByVendor(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getByVendor(false);
    }
    public function getAmount(): void
    {
        try {
            if ($this->QUANTITY) {
                $qty = $this->QUANTITY > 0 ? $this->QUANTITY : 1;
                $this->AMOUNT = $qty * $this->UNIT_COST;
                $this->RETAIL_VALUE = $qty * $this->UNIT_PRICE;
            } else {
                $this->QUANTITY = 1;
                $this->AMOUNT = 0;
                $this->RETAIL_VALUE = 0;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function updatedquantity()
    {
        $this->getAmount();
    }
    public function updateditemid()
    {
        $this->UNIT_ID = 0;
        $this->UNIT_BASE_QUANTITY = 1;
        $this->QUANTITY = 1;
        $this->UNIT_COST = 0;

        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->BATCH_ID = 0;

        $this->unitList = [];

        if ($this->ITEM_ID > 0) {

            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {
                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;
                $this->UNIT_COST = $item->COST ?? 0;
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?? 0;
                $this->getAmount();
            }
        }
    }
    public function mount()
    {
        $this->QUANTITY = 0;
        $this->UNIT_COST = 0;
        $this->UNIT_PRICE = 0;
        $this->AMOUNT = 0;
        $this->RETAIL_VALUE = 0;
        $this->updatedcodeBase();

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
                'QUANTITY' => 'Quantitity',

            ]
        );

        try {

            $this->inventoryAdjustmentServices->ItemStore(
                $this->INVENTORY_ADJUSTMENT_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_COST,
                $this->ASSET_ACCOUNT_ID,
                $this->BATCH_ID,
                $this->UNIT_ID,
                $this->UNIT_BASE_QUANTITY
            );

            $this->ITEM_ID = 0;
            $this->QUANTITY = 0;
            $this->UNIT_ID = 0;
            $this->UNIT_BASE_QUANTITY = 1;
            $this->UNIT_COST = 0;
            $this->UNIT_PRICE = 0;
            $this->AMOUNT = 0;
            $this->RETAIL_VALUE = 0;
            $this->ITEM_CODE = '';
            $this->ITEM_DESCRIPTION = '';
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->updatedcodeBase();
            $this->dispatch('update-amount');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function updatedlineqty()
    {
        $this->getEditAmount();
    }
    public function updatedlinerate()
    {
        $this->getEditAmount();
    }
    public function getEditAmount()
    {
    }
    public function editItem(int $ID)
    {
        $data = $this->inventoryAdjustmentServices->GetItem($ID, $this->INVENTORY_ADJUSTMENT_ID);

        if ($data) {
            $this->editItemId = $data->ID;
            $this->lineQty = $data->QUANTITY;
            $this->lineUnitId = $data->UNIT_ID ?? 0;
            $this->lineUnitCost = $data->UNIT_COST ?? 0;
            $this->lineItemId = $data->ITEM_ID;
            $this->lineBatchId = $data->BATCH_ID ?? 0;
            $this->getEditAmount();
        }
    }

    public function updateItem()
    {

        try {
            $this->inventoryAdjustmentServices->ItemUpdate(
                $this->editItemId,
                $this->INVENTORY_ADJUSTMENT_ID,
                $this->lineItemId,
                $this->lineQty, 
                $this->lineUnitCost,
                $this->lineBatchId,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                1
            );
            $this->cancelItem();

        } catch (\Exception $e) {

            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem()
    {
        $this->editItemId = null;
        $this->lineQty = 0;
        $this->lineUnitId = 0;
        $this->lineItemId = 0;
        $this->lineBatchId = 0;

    }

    public function deleteItem($Id)
    {
        try {
            $this->inventoryAdjustmentServices->ItemDelete(
                $Id,
                $this->INVENTORY_ADJUSTMENT_ID
            );
            $this->dispatch('update-amount');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function getReload()
    {
        $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->itemList = $this->inventoryAdjustmentServices->ItemView($this->INVENTORY_ADJUSTMENT_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.inventory-adjustment.inventory-adjustment-form-items');
    }
}
