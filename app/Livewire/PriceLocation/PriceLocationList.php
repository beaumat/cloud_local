<?php

namespace App\Livewire\PriceLocation;

use App\Services\ItemServices;
use App\Services\ItemSubClassServices;
use App\Services\LocationServices;
use App\Services\PriceLevelLineServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Price List By Location')]
class PriceLocationList extends Component
{

    public $editId = null;
    public $editPrice = 0;


    public $search = '';
    public $dataList = [];
    public $locationList = [];
    public $subList = [];
    public  int $LOCATION_ID;
    public int $SUB_CLASS_ID = 0;
    private $locationServices;
    private $priceLevelLineServices;

    private $itemServices;
    private $userServices;
    private $itemSubClassServices;
    public function boot(
        ItemServices $itemServices,
        UserServices $userServices,
        LocationServices $locationServices,
        PriceLevelLineServices $priceLevelLineServices,
        ItemSubClassServices $itemSubClassServices
    ) {
        $this->locationServices = $locationServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->userServices = $userServices;
        $this->itemServices = $itemServices;
        $this->itemSubClassServices = $itemSubClassServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->subList = $this->itemSubClassServices->getList();
    }
    public function edit($id)
    {
        $this->editId = $id;
        $this->editPrice =  $this->priceLevelLineServices->PriceExists($this->editId, $this->LOCATION_ID);
    }
    public function cancel()
    {
        $this->editId = null;
        $this->editPrice  = 0;
    }
    public function autoUpdate()
    {
        $locData =  $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            if ($locData->PRICE_LEVEL_ID > 0) {

                $data = $this->itemServices->SearchPriceLocation("", 300);
                foreach ($data as $list) {
                    $this->UpdateItem($locData->PRICE_LEVEL_ID, $list->ID, $this->LOCATION_ID, $list->RATE);
                }
            }
        }
    }
    public function save()
    {

        $locData =  $this->locationServices->get($this->LOCATION_ID);
        if ($locData) {
            if ($locData->PRICE_LEVEL_ID > 0) {
                $this->UpdateItem($locData->PRICE_LEVEL_ID, $this->editId, $this->LOCATION_ID, $this->editPrice);
                $this->cancel();
            }
        }
    }

    private function UpdateItem(int $PRICE_LEVEL_ID, int $ITEM_ID, int $LOCATION_ID, float $PRICE)
    {
        $isExistsID =  $this->priceLevelLineServices->DataExists($ITEM_ID, $LOCATION_ID);
        if ($isExistsID > 0) {
            $this->priceLevelLineServices->Update($isExistsID, $PRICE);
        } else {
            $this->priceLevelLineServices->Store($PRICE_LEVEL_ID, $ITEM_ID, $PRICE);
        }
    }
    public function render()
    {
        $this->dataList = $this->itemServices->PriceLevelItemList($this->search, $this->SUB_CLASS_ID, $this->LOCATION_ID);

        return view('livewire.price-location.price-location-list');
    }
}
