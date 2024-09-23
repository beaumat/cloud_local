<?php

namespace App\Livewire\ItemPage;

use App\Services\ItemAccountServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemAccounts extends Component
{
    #[Reactive]
    public int $ITEM_ID;

    public  $availableList = [];
    public $selectedList  = [];
    private $itemAccountServices;
    public function boot(ItemAccountServices $itemAccountServices)
    {
        $this->itemAccountServices = $itemAccountServices;
    }
    public function mount()
    {
        
    }

    public function render()
    {   

            $this->availableList = $this->itemAccountServices->AccountAvailable($this->ITEM_ID);
            $this->selectedList = $this->itemAccountServices->AccountSelected($this->ITEM_ID);

        return view('livewire.item-page.item-accounts');
    }
}
