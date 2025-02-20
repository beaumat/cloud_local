<?php

namespace App\Livewire\Location;

use App\Services\ItemServices;
use App\Services\ItemSoaItemizedServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class SoaItemModal extends Component
{

    public int $SOA_ITEM_ID;
    public $dataList = [];
    public bool $showModal = false;
    public $itemDescList = [];
    private $itemSoaItemizedServices;
    public bool $INACTIVE;
    public int $ITEM_ID = 0;
    public bool $refreshItem = false;
    private $itemServices;
    public function boot(ItemSoaItemizedServices $itemSoaItemizedServices, ItemServices $itemServices)
    {
        $this->itemSoaItemizedServices = $itemSoaItemizedServices;
        $this->itemServices = $itemServices;
    }
    #[On('open-actual-base')]
    public function openModal($data)
    {
        $this->ITEM_ID = 0;
        $this->INACTIVE = false;
        $this->SOA_ITEM_ID = $data['SOA_ITEM_ID'];
        $this->itemDescList = $this->itemServices->getByCustomer(false);
        $this->refreshDataItem();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function Delete(int $ID)
    {
        try {
            $this->itemSoaItemizedServices->Delete($ID);
        } catch (\Throwable $th) {
            session()->flash('error', 'Error:' . $th->getMessage());
        }
    }
    public function Add()
    {
        $this->validate([
            'ITEM_ID' => 'required|exists:item,id'
        ], [], [
            'ITEM_ID' => 'Item'
        ]);


        if ($this->itemSoaItemizedServices->itemExist($this->ITEM_ID, $this->SOA_ITEM_ID)) {
            session()->flash('error', 'Invalid item already exists');
            return;
        }



        try {
            $this->itemSoaItemizedServices->Store($this->ITEM_ID, $this->SOA_ITEM_ID);
            $this->ITEM_ID = 0;
            $this->refreshDataItem();
        } catch (\Throwable $th) {
            session()->flash('error', 'Error:' . $th->getMessage());
        }

    }
    public function refreshDataItem()
    {
        $this->refreshItem = $this->refreshItem ? false : true;
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->itemSoaItemizedServices->List($this->SOA_ITEM_ID);
        }


        return view('livewire.location.soa-item-modal');
    }
}
