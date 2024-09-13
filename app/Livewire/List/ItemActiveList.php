<?php

namespace App\Livewire\List;

use App\Exports\InventoryListItemExport;
use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\UserServices;


use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Item Inventory')]
class ItemActiveList extends Component
{
  public $search = '';
  private $userServices;
  private $dateServices;
  private $locationServices;
  public int $LOCATION_ID;
  public $locationList = [];
  public $DATE;
  private $itemServices;
  public bool $isDesc = false;
  public string $sortby;
  public $dataList = [];
  public $showOutofStock = false;

  public function OnClick(int $ID)
  {
    $data = [
      'ITEM_ID'     => $ID,
      'LOCATION_ID' => $this->LOCATION_ID,
      'showModal'   => true
    ];

    $this->dispatch('open-modal', result: $data);
  }
  public function boot(UserServices $userServices, DateServices $dateServices, LocationServices $locationServices, ItemServices $itemServices)
  {
    $this->userServices = $userServices;
    $this->dateServices = $dateServices;
    $this->locationServices = $locationServices;
    $this->itemServices = $itemServices;
  }
  public function mount()
  {
    $this->sortby = 'c.DESCRIPTION';
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->DATE = $this->dateServices->NowDate();
    $this->locationList = $this->locationServices->getList();
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->refreshItem();
  }
  public function updatedshowOutofStock()
  {
    $this->dataList = $this->itemServices->getActiveItems($this->search, $this->LOCATION_ID, $this->sortby, $this->isDesc, $this->showOutofStock);
  }
  public function sorting(string $column)
  {
    if ($this->sortby  == $column) {
      $this->isDesc = $this->isDesc ? false : true;
      $this->refreshItem();
      return;
    }
    $this->isDesc = $this->isDesc ? false : true;
    $this->sortby = $column;
    $this->refreshItem();
  }
  public function updatedsearch()
  {
    $this->refreshItem();
  }
  public function updatedLocationId()
  {
    $this->refreshItem();
  }
  private function refreshItem()
  {
    $this->dataList = $this->itemServices->getActiveItems($this->search, $this->LOCATION_ID, $this->sortby, $this->isDesc, $this->showOutofStock);
  }
  public function exportData()
  {
    $newData = $this->itemServices->getActiveItems($this->search, $this->LOCATION_ID, $this->sortby, $this->isDesc, $this->showOutofStock);
    return Excel::download(new InventoryListItemExport(
      $newData
    ), "Item-Inventory.xlsx");
  }
  public function render()
  {

    return view('livewire.list.item-active-list');
  }
}
