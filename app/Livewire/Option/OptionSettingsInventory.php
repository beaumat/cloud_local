<?php

namespace App\Livewire\Option;

use App\Models\StockType;
use App\Services\SystemSettingServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Illuminate\Support\Str;

class OptionSettingsInventory extends Component
{
    #[Reactive]
    public $systemSetting = [];
    public $stockTypeList = [];
    public $forcastType = [];
    public int $DefaultForecastingType;
    public int $DefaultItemStockType;
    public bool $DefaultItemTaxable;
    public int $SafetyStockPctLevel;

    public bool $ShowBatchNo, $ShowExpiryDate, $ShowLastPurchaseInfo, $ShowQtyOnSO, $ShowStockBin, $ShowUnitCost;

    public bool $AllowZeroOnHand,$LockQtyNeededInBuildAssembly,$SkipInventoryEntry;

    public function mount()
    {
        $this->stockTypeList = StockType::all();
        $this->DefaultItemStockType = (int) $this->returnArray('DefaultItemStockType');
        $this->DefaultItemTaxable = (bool) $this->returnArray('DefaultItemTaxable');

        $this->forcastType = [['ID' => 0, 'NAME' => 'Weekly'], ['ID' => 1, 'NAME' => 'Monthly']];
        $this->DefaultForecastingType = (int) $this->returnArray('DefaultForecastingType');
        $this->SafetyStockPctLevel = (int) $this->returnArray('SafetyStockPctLevel');

        $this->ShowBatchNo = (bool) $this->returnArray('ShowBatchNo');
        $this->ShowExpiryDate = (bool) $this->returnArray('ShowExpiryDate');
        $this->ShowLastPurchaseInfo = (bool) $this->returnArray('ShowLastPurchaseInfo');
        $this->ShowQtyOnSO = (bool) $this->returnArray('ShowQtyOnSO');
        $this->ShowStockBin = (bool) $this->returnArray('ShowStockBin');
        $this->ShowUnitCost = (bool) $this->returnArray('ShowUnitCost');    

        $this->AllowZeroOnHand = (bool) $this->returnArray('AllowZeroOnHand');  
        $this->LockQtyNeededInBuildAssembly = (bool) $this->returnArray('LockQtyNeededInBuildAssembly');  
        $this->SkipInventoryEntry = (bool) $this->returnArray('SkipInventoryEntry');  
        
    }
    public function returnArray($name): string
    {
        foreach ($this->systemSetting as $list) {
            if (Str::lower($list->NAME) == Str::lower($name)) {
                return $list->VALUE;
            }
        }
        dd("record not found : " . $name);
    }
    public function saveOn($name, $value, SystemSettingServices $systemSettingServices)
    {
        $systemSettingServices->SetValue($name, $value);
        session()->flash('message', $name . ' HAS BEEN SAVE!');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.option.option-settings-inventory');
    }
}
