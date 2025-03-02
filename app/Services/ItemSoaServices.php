<?php

namespace App\Services;

use App\Models\ItemSoa;
use App\Models\ItemSoaType;

class ItemSoaServices
{

    private $object;
    private $itemSoaItemizedServices;
    public function __construct(ObjectServices $objectServices, ItemSoaItemizedServices $itemSoaItemizedServices)
    {

        $this->object = $objectServices;
        $this->itemSoaItemizedServices = $itemSoaItemizedServices;
    }

    public function TypeList()
    {

        $result = ItemSoaType::get();

        return $result;
    }
    public function Get(int $ID)
    {
        $result = ItemSoa::where('ID', '=', $ID)->first();
        return $result;
    }
    private function NextLine(int $TYPE, int $LOCATION_ID)
    {
        return (int) ItemSoa::where('TYPE', '=', $TYPE)->where('LOCATION_ID', '=', $LOCATION_ID)->max('LINE') + 1;
    }
    public function Store(int $LOCATION_ID, int $TYPE, int $LINE, string $ITEM_NAME, string $UNIT_NAME, float $RATE, bool $ACTUAL_BASE = false, string $DOSAGE, string $ROUTE, string $FREQUENCY)
    {
        $ID = $this->object->ObjectNextID('SOA_ITEM');



        ItemSoa::create(
            [
                'ID' => $ID,
                'LOCATION_ID' => $LOCATION_ID,
                'LINE' => $LINE > 0 ? $LINE : $this->NextLine($TYPE, $LOCATION_ID),
                'TYPE' => $TYPE,
                'ITEM_NAME' => $ITEM_NAME,
                'UNIT_NAME' => $UNIT_NAME,
                'RATE' => $RATE,
                'ACTUAL_BASE' => $ACTUAL_BASE,
                'DOSAGE' => $DOSAGE,
                'ROUTE' => $ROUTE,
                'FREQUENCY' => $FREQUENCY
            ]
        );

        return $ID;
    }
    public function Update(int $ID, int $TYPE, int $LINE, string $ITEM_NAME, string $UNIT_NAME, float $RATE, bool $ACTUAL_BASE = false, string $DOSAGE, string $ROUTE, string $FREQUENCY)
    {
        ItemSoa::where('ID', '=', $ID)
            ->update(
                [
                    'ID' => $ID,
                    'TYPE' => $TYPE,
                    'LINE' => $LINE,
                    'ITEM_NAME' => $ITEM_NAME,
                    'UNIT_NAME' => $UNIT_NAME,
                    'RATE' => $RATE,
                    'ACTUAL_BASE' => $ACTUAL_BASE,
                    'DOSAGE' => $DOSAGE,
                    'ROUTE' => $ROUTE,
                    'FREQUENCY' => $FREQUENCY
                ]
            );
    }
    public function Delete(int $ID)
    {
        ItemSoa::where('ID', '=', $ID)
            ->delete();
    }

    public function Search($search, int $LOCATION_ID): object
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.LINE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY'

            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('soa_item_type.DESCRIPTION', 'like', "%$search%")
                        ->orWhere('ITEM_NAME', 'like', "%$search%")
                        ->orWhere('UNIT_NAME', 'like', "%$search%");
                });
            })
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }

    public function GetList(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE'
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }

    public function GetMedicineList(int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE',
                'soa_item.ACTUAL_BASE',
                'soa_item.DOSAGE',
                'soa_item.ROUTE',
                'soa_item.FREQUENCY'

            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('TYPE', '=', 1)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;

    }
    public function getItemByCategory(int $LOCATION_ID, int $TYPE)
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->get();


        return $result;
    }
    public function getItemBySingleCategoryWithSum(int $LOCATION_ID, int $TYPE)
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->sum('RATE');

        if ($result > 0) {
            return (float) $result;
        }
        return 0.00;
    }
    public function getSumNonActualByType(int $TYPE, int $LOCATION_ID): float
    {
        $result = ItemSoa::where('TYPE', '=', $TYPE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('ACTUAL_BASE', '=', false)
            ->sum('RATE');

        if ($result > 0) {
            return (float) $result;
        }
        return 0.00;
    }
    public function haveDataExist(int $LOC_ID): bool
    {
        return ItemSoa::where('LOCATION_ID', '=', $LOC_ID)->exists();
    }
    public function copyEntryToAnotherLocation(int $FORM_LOCATION_ID, int $TO_LOCATION_ID)
    {

        $fromDataList = ItemSoa::where('LOCATION_ID', '=', $FORM_LOCATION_ID)->get();
        foreach ($fromDataList as $list) {
            $NEW_ID = $this->Store($TO_LOCATION_ID, $list->TYPE, $list->LINE, $list->ITEM_NAME, $list->UNIT_NAME, $list->RATE, $list->ACTUAL_BASE, $list->DOSAGE, $list->ROUTE, $list->FREQUENCY);
            if ($list->ACTUAL_BASE) {
                $dataItem = $this->itemSoaItemizedServices->GetList($list->ID);
                foreach ($dataItem as $itemList) {
                    $this->itemSoaItemizedServices->Store($itemList->ITEM_ID, $NEW_ID);
                }
            }
        }


    }


}
