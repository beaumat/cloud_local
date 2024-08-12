<?php

namespace App\Livewire\PullOut;

use App\Services\ItemServices;
use App\Services\PullOutServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PullOutFormItems extends Component
{
    #[Reactive]
    public int $PULL_OUT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $openStatus;

    public int $ID;
    public int $ITEM_ID = 0;
    public string $ITEM_CODE;
    public string $ITEM_DESCRIPTION;
    public float $QUANTITY;
    public int $UNIT_ID;

    public float $RATE;
    public float $AMOUNT;
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
    public float $lineRate;
    public float $lineAmount;
    public int $lineBatchId;
    public $editUnitList = [];
    public int $lineItemId = 0;
    private $pullOutServices;
    private $unitOfMeasureServices;
    private $itemServices;
    public function boot(
        PullOutServices $pullOutServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemServices $itemServices,
    ) {
        $this->pullOutServices = $pullOutServices;
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
                $this->AMOUNT = $qty * $this->RATE;
            } else {
                $this->QUANTITY = 1;
                $this->AMOUNT = 0;
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
        $this->QUANTITY = 1;
        $this->RATE = 0;

        $this->ITEM_CODE = '';
        $this->ITEM_DESCRIPTION = '';
        $this->BATCH_ID = 0;
        $this->AMOUNT = 0;
        $this->unitList = [];

        if ($this->ITEM_ID > 0) {
            $item = $this->itemServices->get($this->ITEM_ID);
            if ($item) {

                $this->ITEM_CODE = $item->CODE;
                $this->ITEM_DESCRIPTION = $item->DESCRIPTION;

                $this->RATE = $item->RATE ?? 0;
                $this->UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?? 0;

                $this->getAmount();
            }
        }
    }
    public function mount()
    {
        $this->QUANTITY = 0;
        $this->RATE = 0;

        $this->AMOUNT = 0;
        $this->updatedcodeBase();
    }
    public function saveItem()
    {
        $this->validate(
            [
                'ITEM_ID' => 'required|not_in:0',
                'QUANTITY' => 'required|numeric|not_in:0',

            ],
            [],
            [
                'ITEM_ID' => 'Item',
                'QUANTITY' => 'Quantity',

            ]
        );

        try {

            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->ITEM_ID, $this->UNIT_ID ?? 0);

            $this->pullOutServices->ItemStore(
                $this->PULL_OUT_ID,
                $this->ITEM_ID,
                $this->QUANTITY,
                $this->UNIT_ID,
                (float) $unitRelated['QUANTITY'],
                $this->RATE,
                $this->BATCH_ID,
                $this->ASSET_ACCOUNT_ID
            );

            $this->ITEM_ID = 0;
            $this->QUANTITY = 0;
            $this->UNIT_ID = 0;
            $this->RATE = 0;
            $this->AMOUNT = 0;
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
                $this->lineAmount = $qty * $this->lineRate;
            } else {
                $this->lineQty = 1;
                $this->lineAmount = 0;
            }
        } catch (\Throwable $th) {
        }
    }
    public function editItem(int $ID)
    {
        $data = $this->pullOutServices->GetItem($ID, $this->PULL_OUT_ID);
        if ($data) {
            $this->editItemId = $data->ID;
            $this->lineQty = $data->QUANTITY;
            $this->lineUnitId = $data->UNIT_ID ?? 0;
            $this->lineRate = $data->UNIT_COST ?? 0;
            $this->lineAmount = $data->AMOUNT ?? 0;
            $this->lineItemId = $data->ITEM_ID;
            $this->lineBatchId = $data->BATCH_ID ?? 0;
            $this->getEditAmount();
        }
    }

    public function updateItem()
    {
        $this->validate(
            [
                'lineQty' => 'required|not_in:0',
            ],
            [],
            [
                'lineQty' => 'Quantity',
            ]
        );

        try {
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($this->lineItemId, $this->lineUnitId ?? 0);

            $this->pullOutServices->ItemUpdate(
                $this->editItemId,
                $this->PULL_OUT_ID,
                $this->lineItemId,
                $this->lineQty,
                $this->lineUnitId > 0 ? $this->lineUnitId : 0,
                (float) $unitRelated['QUANTITY'],
                $this->lineRate,
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
        $this->lineRate = 0;
        $this->lineAmount = 0;
        $this->lineItemId = 0;
        $this->lineBatchId = 0;
    }

    public function deleteItem($Id)
    {
        try {
            $this->pullOutServices->ItemDelete(
                $Id,
                $this->PULL_OUT_ID
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
        $this->itemList = $this->pullOutServices->ItemView($this->PULL_OUT_ID);
    }
    public function render()
    {
        $this->getReload();
        return view('livewire.pull-out.pull-out-form-items');
    }
}
