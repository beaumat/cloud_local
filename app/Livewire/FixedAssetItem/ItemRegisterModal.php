<?php

namespace App\Livewire\FixedAssetItem;

use App\Services\FixedAssetItemServices;
use App\Services\ItemServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemRegisterModal extends Component
{
    #[Reactive]
    public $LOCATION_ID;
    public $search;

    public  $dataList = [];
    public bool $showModal = false;
    private $itemServices;
    private $fixedAssetItemServices;
    public function boot(ItemServices $itemServices, FixedAssetItemServices $fixedAssetItemServices)
    {
        $this->itemServices = $itemServices;
        $this->fixedAssetItemServices = $fixedAssetItemServices;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function Add(int $ITEM_ID)
    {   

        $this->fixedAssetItemServices->Store(
            $ITEM_ID,
            $this->LOCATION_ID,
            0,
            0,
            '',
            '',
            '',
            false,
            false,
            ''
        );

        $this->dispatch('refresh-list');
    }
    public function render()
    {
        $this->dataList = $this->itemServices->InventoryItemToFixedAsset($this->search, $this->LOCATION_ID);
        return view('livewire.fixed-asset-item.item-register-modal');
    }
}
