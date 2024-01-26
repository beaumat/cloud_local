<?php

namespace App\Livewire;

use App\Models\PriceLevels;
use App\Services\PriceLevelLineServices;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemPriceLevelPanel extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public int $PRICE_LEVEL_ID;
    public float $CUSTOM_PRICE;
    public $priceLevels = [];
    public $priceLevelList = [];
    public $search = null;
    public float $newCustomPrice;

    public bool $saveSuccess = false;

    public $editItemId = null;

    public function mount($itemId)
    {
        $this->itemId = $itemId;
        $this->reloadPriceLevel();
    }
    public function reloadPriceLevel()
    {
        $this->priceLevels = PriceLevels::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();
    }
    public function saveItem(PriceLevelLineServices $priceLevelLineServices)
    {
    
        $this->validate(
            [
                'PRICE_LEVEL_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('price_level_lines', 'price_level_id')->where(function ($query) {
                        return $query->where('price_level_id', $this->PRICE_LEVEL_ID)->where('item_id', $this->itemId);
                    }),
                ],
                'CUSTOM_PRICE' => 'required|not_in:0',
            ],
            [],
            [
                'PRICE_LEVEL_ID' => 'Price Level',
                'CUSTOM_PRICE' => 'Custom Price',
            ]
        );

        try {
            $priceLevelLineServices->Store($this->PRICE_LEVEL_ID, $this->itemId, $this->CUSTOM_PRICE);
            $this->priceLevelList = $priceLevelLineServices->LoadPriceLevelByItem($this->itemId);
            $this->CUSTOM_PRICE = 0;
            $this->PRICE_LEVEL_ID = 0;
            $this->saveSuccess = $this->saveSuccess ? false : true;
            $this->reloadPriceLevel();
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function editItem($id, $custPrice)
    {
        $this->newCustomPrice = $custPrice;
        $this->editItemId = $id;
    }
    public function updateItem($id, PriceLevelLineServices $priceLevelLineServices)
    {

        try {
            $priceLevelLineServices->Update($id, $this->newCustomPrice);
            $this->editItemId = null;
            $this->priceLevelList = $priceLevelLineServices->LoadPriceLevelByItem($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function cancelItem():void
    {
        $this->editItemId = null;
    }
    public function deleteItem($id, PriceLevelLineServices $priceLevelLineServices): void
    {

        try {
            $priceLevelLineServices->Delete($id);
            $this->priceLevelList = $priceLevelLineServices->LoadPriceLevelByItem($this->itemId);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render(PriceLevelLineServices $priceLevelLineServices)
    {
        $this->priceLevelList = $priceLevelLineServices->LoadPriceLevelByItem($this->itemId);
        return view('livewire.item-price-level-panel');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
}
