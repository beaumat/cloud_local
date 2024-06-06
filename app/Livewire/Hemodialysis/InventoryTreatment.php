<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use App\Services\ItemServices;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InventoryTreatment extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $STATUS;
    public int $openStatus = 0;
    public bool $saveSuccess = false;
    public $dataList = [];
    private $hemoServices;
    private $itemServices;
    private $unitOfMeasureServices;

    public function boot(
        HemoServices $hemoServices,
        ItemServices $itemServices,
        UnitOfMeasureServices $unitOfMeasureServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->itemServices = $itemServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
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
    public float $UNIT_BASE_QUANTITY;
    public bool $IS_NEW;
    public int $BASE_UNIT_ID;
    public function mount()
    {
        $this->codeBase = false;
        $this->updatedcodeBase();
    }
    public function updatedItemId()
    {
        $this->UNIT_ID = 0;
        $this->UNIT_BASE_QUANTITY = 1;
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
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID > 0 ? $item->BASE_UNIT_ID : 1;
            }
        }
    }

    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = $this->itemServices->getInventoryItem(true);
            return;
        }
        $this->itemDescList = $this->itemServices->getInventoryItem(false);
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

        $this->hemoServices->ItemStore($this->HEMO_ID, $this->ITEM_ID, $this->QUANTITY, $this->UNIT_ID, $this->UNIT_BASE_QUANTITY, $this->IS_NEW);
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
    public $lineItemId = null;
    public float $lineQty;
    public int $lineUnitId;
    public $lineIsNew;


    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        if ($this->lineItemId > 0) {
            $this->editUnitList = $this->unitOfMeasureServices->ItemUnit($this->lineItemId);
        }

        $this->unitList = $this->unitOfMeasureServices->ItemUnit($this->ITEM_ID);
        $this->dataList = $this->hemoServices->ItemView($this->HEMO_ID);
        return view('livewire.hemodialysis.inventory-treatment');
    }
}
