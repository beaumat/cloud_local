<?php

namespace App\Livewire\List;

use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Active Item List')]
class ItemActiveList extends Component
{
  private $userServices;
  private $dateServices;
  private $locationServices;
  public int $LOCATION_ID;
  public $locationList = [];
  public $DATE;


  public function boot(
    UserServices $userServices,
    DateServices $dateServices,
    LocationServices $locationServices
  ) {
    $this->userServices = $userServices;
    $this->dateServices = $dateServices;
    $this->locationServices = $locationServices;
  }
  public function mount()
  {
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
    $this->DATE = $this->dateServices->NowDate();
    $this->locationList = $this->locationServices->getList();
    $this->LOCATION_ID = $this->userServices->getLocationDefault();
  }
  public function getItems($locationId)
  {
    $items = DB::table('item')
      ->select(
        [
          'item.ID',
          'item.CODE',
          'item.DESCRIPTION',
          'item.RATE',
          'item.COST',
          't.DESCRIPTION as TYPE'
        ]
      )
      ->selectSub(function ($query) use (&$locationId) {
        $query->from('item_inventory')
          ->select('item_inventory.ENDING_QUANTITY')
          ->whereColumn('item_inventory.ITEM_ID', 'item.ID')
          ->where('item_inventory.LOCATION_ID', $locationId)
          ->orderBy('item_inventory.SOURCE_REF_DATE', 'DESC')
          ->limit(1);
      }, 'QTY_ON_HAND')
      ->leftJoin('item_type_map as t', 't.ID', '=', 'item.TYPE')
      ->where('item.TYPE', '<', 2)
      ->where('item.INACTIVE', 0)
      ->get();

    return $items;
  }
  public function render()
  {
    $dataList = $this->getItems($this->LOCATION_ID);
    return view('livewire.list.item-active-list', ['dataList' => $dataList]);
  }
}
