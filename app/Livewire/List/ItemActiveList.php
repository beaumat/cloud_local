<?php

namespace App\Livewire\List;

use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\UserServices;

use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Active Item List')]
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
  public $dataList = [];

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
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->DATE = $this->dateServices->NowDate();
    $this->locationList = $this->locationServices->getList();
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
  }
  public bool $isDesc = false;
  public string $sortby = 'c.DESCRIPTION';
  public function sorting(string $column)
  {
    if ($this->sortby  == $column) {
      $this->isDesc = $this->isDesc ? false : true;
      return;
    }
    $this->isDesc = true;
    $this->sortby = $column;
  }
  public function render()
  {
    $this->dataList = $this->itemServices->getActiveItems($this->search, $this->LOCATION_ID, $this->sortby, $this->isDesc);
    return view('livewire.list.item-active-list');
  }
}
