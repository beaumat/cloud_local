<?php

namespace App\Livewire\List;

use Livewire\Component;

class InventoryDetailsModal extends Component
{

    public bool $showModal = false;
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function render()
    {
        return view('livewire.list.inventory-details-modal');
    }
}
