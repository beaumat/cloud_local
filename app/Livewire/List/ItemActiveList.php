<?php

namespace App\Livewire\List;

use App\Services\DateServices;
use App\Services\ItemServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Active Item List')]
class ItemActiveList extends Component
{

  use WithPagination;
  protected $paginationTheme = 'bootstrap';
  public $search = '';
  public int $perPage = 15;

  private $userServices;
  private $dateServices;
  private $locationServices;
  public int $LOCATION_ID;
  public $locationList = [];
  public $DATE;
  private $itemServices;
  public function boot(
    UserServices $userServices,
    DateServices $dateServices,
    LocationServices $locationServices,
    ItemServices $itemServices

  ) {
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
  public function getItems()
  {
    return $this->itemServices->getActiveItems($this->search, $this->LOCATION_ID, $this->perPage);
  }
  public function render()
  {
    $dataList = $this->getItems();
    return view('livewire.list.item-active-list', ['dataList' => $dataList]);
  }
}
