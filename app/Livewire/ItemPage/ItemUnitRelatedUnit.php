<?php

namespace App\Livewire\ItemPage;

use App\Models\UnitOfMeasures;
use App\Services\ItemUnitServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemUnitRelatedUnit extends Component
{   
    #[Reactive]
    public int $itemId = 0;
    public int $UNIT_ID;
    public float $QUANTITY;
    public float $RATE;
    public string $BARCODE;

    public bool $saveSuccess = false;
    public $units = [];
    public $unitRelatedList = [];

    public $editItemId = null;

    public float $newQUANTITY;
    public float $newRATE;
    public string $newBARCODE;

    public function mount($itemId)
    {

        $this->itemId = $itemId;
        $this->dowpDownLoad();
        $this->UNIT_ID = 0;
        $this->QUANTITY = 0;
        $this->RATE = 0;
        $this->BARCODE = '';
    }
    public function dowpDownLoad()
    {
        $this->units =  UnitOfMeasures::where('INACTIVE', '0')->get();
    }
    public function saveItem(ItemUnitServices $itemUnitServices)
    {

        $this->validate(
            [
                'UNIT_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_units', 'unit_id')->where(function ($query) {
                        return $query->where('unit_id', $this->UNIT_ID)->where('item_id', $this->itemId);
                    }),
                ],
                'QUANTITY' => 'required|not_in:0,unit_id',
                'RATE' => 'required|not_in:0,unit_id',
            ],
            [],
            [
                'UNIT_ID' => 'Unit',
                'QUANTITY' => 'Quantity',
                'RATE' => 'Rate'
            ]
        );
        try {
            $itemUnitServices->Store(
                $this->itemId,
                $this->UNIT_ID,
                $this->QUANTITY,
                $this->RATE,
                $this->BARCODE
            );

            $this->dowpDownLoad();
            $this->UNIT_ID = 0;
            $this->QUANTITY = 0;
            $this->RATE = 0;
            $this->BARCODE = '';
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->unitRelatedList = $itemUnitServices->Search($this->itemId);
            $this->dispatch('reload-related');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }    public function editItem(int $id, float $Qty, float $rate, string $barcode): void
    {
  
        $this->newQUANTITY = $Qty ? $Qty : 0;
        $this->newRATE = $rate ? $rate : 0;
        $this->newBARCODE = $barcode ? $barcode : '';
        $this->editItemId = $id;
    }
    public function updateItem($id, ItemUnitServices $itemUnitServices): void
    {

        $this->validate(
            [   
                'newQUANTITY' => 'required|not_in:0,unit_id',
                'newRATE' => 'required|not_in:0,unit_id',
            ],
            [],
            [    
                'newQUANTITY' => 'Quantity',
                'newRATE' => 'Rate'
            ]
        );

        try {
            $itemUnitServices->Update($id, $this->newQUANTITY, $this->newRATE, $this->newBARCODE);
            $this->editItemId = null;
            $this->unitRelatedList = $itemUnitServices->Search($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem(): void
    {
        $this->editItemId = null;
    }
    public function deleteItem(int $ID, ItemUnitServices $itemUnitServices): void
    {
        try {
            $itemUnitServices->Delete($ID);
            $this->unitRelatedList = $itemUnitServices->Search($this->itemId);
            $this->dispatch('reload-related');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render(ItemUnitServices $itemUnitServices)
    {
        $this->unitRelatedList = $itemUnitServices->Search($this->itemId);

        return view('livewire.item-page.item-unit-related-unit');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }
}
