<?php

namespace App\Livewire\StockTransfer;

use App\Services\ItemServices;
use App\Services\StockTransferServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class StockTransferFormItems extends Component
{
    #[Reactive]
    public int $STOCK_TRANSFER_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $openStatus ;

    public int $ID;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public int $UNIT_ID;
    public float $UNIT_BASE_QUANTITY;
    public float $UNIT_COST;
    public float $UNIT_PRICE;
    public float $AMOUNT;
    public float $RETAIL_VALUE;
    public int $ASSET_ACCOUNT_ID;
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
    public float $lineUnitCost;
    public float $lineUnitPrice;
    public float $lineAmount;
    public float $lineRetailValue;
    public int $lineBatchId;
    public $editUnitList = [];
    public int $lineItemId = 0;
    private $stockTransferServices;
    private $unitOfMeasureServices;
    private $itemServices;
    public function boot(
        StockTransferServices $stockTransferServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemServices $itemServices,
    ) {
        $this->stockTransferServices = $stockTransferServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemServices = $itemServices;
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getInventoryItem(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getInventoryItem(false);
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
    public function updatedunitprice()
    {
        $this->getAmount();
    }
    public function updateditemid()
    {
        $this->UNIT_ID = 0;
        $this->UNIT_BASE_QUANTITY = 1;
        $this->QUANTITY = 1;
        $this->UNIT_COST = 0;
        $this->UNIT_PRICE = 0;

        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->BATCH_ID = 0;
        $this->AMOUNT = 0;
        $this->RETAIL_VALUE = 0;
        $this->unitList = [];

        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {

                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;

                $this->UNIT_COST = $item->COST ?? 0;
                $this->UNIT_PRICE = $item->RATE ?? 0;
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

            $this->stockTransferServices->ItemStore(
                $this->STOCK_TRANSFER_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID,
                $this->UNIT_BASE_QUANTITY,
                $this->UNIT_COST,
                $this->UNIT_PRICE,
                $this->BATCH_ID,
                $this->ASSET_ACCOUNT_ID
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
        try {
            if ($this->lineQty) {
                $qty = $this->lineQty > 0 ? $this->lineQty : 1;
                $this->lineRetailValue = $qty * $this->lineUnitPrice;
                $this->lineAmount = $qty * $this->lineUnitCost;
            } else {
                $this->lineQty = 1;
                $this->lineRetailValue = 0;
                $this->lineAmount = 0;
            }
        } catch (\Throwable $th) {
        }
    }
    public function editItem(int $ID)
    {
        $data = $this->stockTransferServices->GetItem($ID, $this->STOCK_TRANSFER_ID);
        if ($data) {
            $this->editItemId = $data->ID;
            $this->lineQty = $data->QUANTITY;
            $this->lineUnitId = $data->UNIT_ID ?? 0;
            $this->lineUnitCost = $data->UNIT_COST ?? 0;
            $this->lineUnitPrice = $data->UNIT_PRICE ?? 0;
            $this->lineAmount = $data->AMOUNT ?? 0;
            $this->lineRetailValue = $data->RETAIL_VALUE ?? 0;
            $this->lineItemId = $data->ITEM_ID;
            $this->lineBatchId = $data->BATCH_ID ?? 0;
            $this->getEditAmount();
        }
    }

    public function updateItem()
    {

        try {
            $this->stockTransferServices->ItemUpdate(
                $this->editItemId,
                $this->STOCK_TRANSFER_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                1,
                $this->lineUnitCost,
                $this->lineUnitPrice,
                $this->lineBatchId

            );
            $this->dispatch('update-amount');
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
        $this->lineUnitCost = 0;
        $this->lineUnitPrice = 0;
        $this->lineAmount = 0;
        $this->lineRetailValue = 0;
        $this->lineItemId = 0;
        $this->lineBatchId = 0;

    }

    public function deleteItem($Id)
    {
        try {
            $this->stockTransferServices->ItemDelete(
                $Id,
                $this->STOCK_TRANSFER_ID
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
        $this->itemList = $this->stockTransferServices->ItemView($this->STOCK_TRANSFER_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.stock-transfer.stock-transfer-form-items');
    }
}
