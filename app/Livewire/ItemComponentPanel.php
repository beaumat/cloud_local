<?php

namespace App\Livewire;

use Illuminate\Validation\Rule;
use App\Models\Items;
use App\Services\ItemComponentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemComponentPanel extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public string  $itemTypeName;
    public bool $saveSuccess = false;
    public bool $codeBase = false;
    public int $COMPONENT_ID;
    public float $QUANTITY = 1;
    public float $RATE = 0;
    public $itemDescList = [];
    public $itemCodeList = [];
    public string $search = '';
    public $componentList = [];

    public  $editItemId = null;
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

    public function deleteComponent($id, ItemComponentServices $itemComponentServices)
    {
        $itemComponentServices->Delete($id);
        $this->componentList =  $itemComponentServices->Search($this->search, $this->itemId);
    }
    public function saveComponent(ItemComponentServices $itemComponentServices)
    {

        $this->validate(
            [
                'COMPONENT_ID' => [
                    'required', 'not_in:0',
                    Rule::unique('item_components', 'component_id')->where(function ($query) {
                        return $query->where('item_id', $this->itemId);
                    }),
                ],
                'QUANTITY' => 'required|not_in:0',
            ]
        );


        try {
            $itemComponentServices->Store(
                $this->COMPONENT_ID,
                $this->itemId,
                $this->QUANTITY ? $this->QUANTITY : 0,
                $this->RATE ? $this->RATE : 0
            );
            $this->componentList =  $itemComponentServices->Search($this->search, $this->itemId);
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
    public function render(ItemComponentServices $itemComponentServices)
    {
        $this->componentList =  $itemComponentServices->Search($this->search, $this->itemId);
        return view('livewire.item-component-panel');
    }
}
