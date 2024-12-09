<?php

namespace App\Livewire\FixedAssetItem;

use App\Models\Accounts;
use App\Models\Contacts;
use App\Models\ItemClass;
use App\Models\ItemGroup;
use App\Models\ItemSubClass;
use App\Models\ItemType;
use App\Models\Manufacturers;
use App\Models\RateType;
use App\Models\StockType;
use App\Models\UnitOfMeasures;
use App\Services\AccountServices;
use App\Services\FixedAssetItemServices;
use App\Services\ItemServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Component;

class FixedAssetItemForm extends Component
{


    public int $ID;
    public int $ITEM_ID;
    public int $LOCATION_ID;
    public int $ACCUMULATED_ACCOUNT_ID;
    public int $DEPRECIATION_ACCOUNT_ID;
    public string $PO_NUMBER;
    public string $SERIAL_NO;
    public string $WARRANTIY_EXPIRED;
    public bool $PERSONAL_PROPERTY_RETURN;
    public bool $IS_NEW;
    public string $OTHER_DESCRIPTION;
    public bool $showModal = false;

    private $fixedAssetItemServices;
    private $accountServices;
    public $accountList = [];
    public function boot(FixedAssetItemServices $fixedAssetItemServices, AccountServices $accountServices)
    {
        $this->fixedAssetItemServices = $fixedAssetItemServices;
        $this->accountServices = $accountServices;
    }


    #[On('open-asset-item')]
    public function openModal($result)
    {
        $ID   = $result['ID'] ?? 0;
        $data = $this->fixedAssetItemServices->Get($ID);

        if ($data) {
            $this->ID = $data->ID;
            $this->ITEM_ID = $data->ITEM_ID ?? 0;
            $this->LOCATION_ID = $data->LOCATION_ID ?? 0;
            $this->ACCUMULATED_ACCOUNT_ID =  $data->ACCUMULATED_ACCOUNT_ID ?? 0;
            $this->DEPRECIATION_ACCOUNT_ID = $data->DEPRECIATION_ACCOUNT_ID ?? 0;
            $this->PO_NUMBER = $data->PO_NUMBER ?? '';
            $this->SERIAL_NO = $data->SERIAL_NO ?? '';
            $this->WARRANTIY_EXPIRED = $data->WARRANTIY_EXPIRED ?? false;
            $this->PERSONAL_PROPERTY_RETURN = $data->PERSONAL_PROPERTY_RETURN ?? false;
            $this->IS_NEW  = $data->IS_NEW ?? false;
            $this->OTHER_DESCRIPTION = $data->OTHER_DESCRIPTION ?? '';
            $this->showModal = true;
            $this->accountList = $this->accountServices->getAccount(false);
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }


    public function save()
    {

        $this->fixedAssetItemServices->Update(
            $this->ID,
            $this->ACCUMULATED_ACCOUNT_ID,
            $this->DEPRECIATION_ACCOUNT_ID,
            $this->PO_NUMBER,
            $this->SERIAL_NO,
            $this->WARRANTIY_EXPIRED,
            $this->PERSONAL_PROPERTY_RETURN,
            $this->IS_NEW,
            $this->OTHER_DESCRIPTION
        );
        $this->dispatch('refresh-list');
        $this->closeModal();
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        return view('livewire.fixed-asset-item.fixed-asset-item-form');
    }
}
