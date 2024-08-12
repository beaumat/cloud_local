<?php

namespace App\Livewire\List;

use App\Services\ItemInventoryServices;
use App\Services\ItemServices;
use Livewire\Attributes\On;
use Livewire\Component;

class InventoryDetailsModal extends Component
{
    public int $ITEM_ID;
    public int $LOCATION_ID;
    public string $ITEM_NAME;
    public $dataList = [];
    public bool $showModal = false;
    private $itemInventoryServices;
    private $itemServices;
    public function boot(ItemInventoryServices $itemInventoryServices, ItemServices $itemServices)
    {
        $this->itemInventoryServices = $itemInventoryServices;
        $this->itemServices = $itemServices;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    #[On("open-modal")]
    public function showingModa($result)
    {
        $this->ITEM_ID = $result['ITEM_ID'];
        $data = $this->itemServices->get($this->ITEM_ID);
        if ($data) {
            $this->LOCATION_ID = $result['LOCATION_ID'];
            $this->showModal = $result['showModal'];
            $this->ITEM_NAME = $data->DESCRIPTION;
            $this->dispatch('active-scroll');
        }
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    #[On('active-scroll')]
    public function scrollDown()
    {
        $this->dispatch('scrollToBottom'); // Emit an event to trigger scrolling
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->itemInventoryServices->getDetails($this->ITEM_ID, $this->LOCATION_ID);
        }

        return view('livewire.list.inventory-details-modal');
    }
}
