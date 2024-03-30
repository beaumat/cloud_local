<?php

namespace App\Livewire\ItemPage;

use Illuminate\Validation\Rule;
use App\Models\Items;
use App\Services\ItemComponentServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemComponentPanel extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public string $itemTypeName;
    public bool $saveSuccess = false;
    public bool $codeBase = false;
    public int $COMPONENT_ID;
    public float $QUANTITY = 1;
    public float $RATE = 0;
    public $itemDescList = [];
    public $itemCodeList = [];
    public string $search = '';
    public $componentList = [];
    public $editItemId = null;
    public float $newQty;
    public float $newRate;

    private $itemComponentServices;
    public function boot(ItemComponentServices $itemComponentServices)
    {
        $this->itemComponentServices = $itemComponentServices;
    }
    public function updatedcodeBase()
    {
        if ($this->codeBase) {
            $this->itemCodeList = Items::query()->select(['ID', 'CODE'])->where('INACTIVE', '0')->whereIn('TYPE', ['0', '2', '3', '4', '7'])
                ->get();
            return;
        }
        $this->itemDescList = Items::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->whereIn('TYPE', ['0', '2', '3', '4', '7'])
            ->get();
    }

    public function saveItem()
    {
        $this->validate(
            [
                'COMPONENT_ID' => [
                    'required',
                    'not_in:0',
                    Rule::unique('item_components', 'component_id')->where(function ($query) {
                        return $query->where('item_id', $this->itemId);
                    }),
                ],
                'QUANTITY' => 'required|not_in:0',
            ],
            [],
            [
                'COMPONENT_ID' => 'Item',
            ]
        );

        try {
            $this->itemComponentServices->Store(
                $this->COMPONENT_ID,
                $this->itemId,
                $this->QUANTITY ? $this->QUANTITY : 0,
                $this->RATE ? $this->RATE : 0
            );
            $this->componentList = $this->itemComponentServices->Search($this->search, $this->itemId);
            $this->COMPONENT_ID = 0;
            $this->RATE = 0;
            $this->QUANTITY = 1;
            $this->updatedcodeBase();
            $this->saveSuccess = $this->saveSuccess ? false : true;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount($itemId, $itemTypeName)
    {
        $this->itemId = intval($itemId);
        $this->itemTypeName = $itemTypeName;
        $this->updatedcodeBase();
    }
    public function editItem($id, $newQty, $newRate)
    {
        $this->newQty = $newQty;
        $this->newRate = $newRate;
        $this->editItemId = $id;
    }
    public function updateItem($id, ItemComponentServices $itemComponentServices)
    {
        $this->validate(
            [
                'newQty' => 'required|not_in:0'
            ],
            [],
            [
                'newQty' => 'Quantity',
            ]
        );

        $itemComponentServices->Update($id, $this->newQty, $this->newRate);
        $this->editItemId = null;
        $this->componentList = $itemComponentServices->Search($this->search, $this->itemId);
    }
    public function cancelItem()
    {
        $this->editItemId = null;
    }
    public function deleteItem($id, ItemComponentServices $itemComponentServices)
    {
        $itemComponentServices->Delete($id);
        $this->componentList = $itemComponentServices->Search($this->search, $this->itemId);
    }
    public function render(ItemComponentServices $itemComponentServices)
    {
        $this->componentList = $itemComponentServices->Search($this->search, $this->itemId);
        return view('livewire.item-page.item-component-panel');
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
