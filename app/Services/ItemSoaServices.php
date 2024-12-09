<?php

namespace App\Services;

use App\Models\ItemSoa;
use App\Models\ItemSoaType;

class ItemSoaServices
{

    private $object;
    public function __construct(ObjectServices $objectServices)
    {

        $this->object = $objectServices;
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
    private function NextLine(int $TYPE)
    {
        return (int) ItemSoa::where('TYPE', $TYPE)->max('LINE') + 1;
    }
    public function Store(int $LOCATION_ID, int $TYPE, string $ITEM_NAME, string $UNIT_NAME, float $RATE)
    {
        $ID = $this->object->ObjectNextID('SOA_ITEM');

        $LINE = $this->NextLine($TYPE);

        ItemSoa::create(
            [
                'ID'            => $ID,
                'LOCATION_ID'   => $LOCATION_ID,
                'LINE'          => $LINE,
                'TYPE'          => $TYPE,
                'ITEM_NAME'     => $ITEM_NAME,
                'UNIT_NAME'     => $UNIT_NAME,
                'RATE'          => $RATE
            ]
        );
    }
    public function Update(int $ID, int $TYPE, string $ITEM_NAME, string $UNIT_NAME, float $RATE)
    {
        ItemSoa::where('ID', '=', $ID)
            ->update(
                [
                    'ID'            => $ID,
                    'TYPE'          => $TYPE,
                    'ITEM_NAME'     => $ITEM_NAME,
                    'UNIT_NAME'     => $UNIT_NAME,
                    'RATE'          => $RATE
                ]
            );
    }
    public function Delete(int $ID)
    {
        ItemSoa::where('ID', '=', $ID)
            ->delete();
    }

    public function Search($search, int $LOCATION_ID)
    {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE'
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

    public function GetList(int $LOCATION_ID) {
        $result = ItemSoa::query()
            ->select([
                'soa_item.ID',
                'soa_item.TYPE',
                'soa_item_type.DESCRIPTION as TYPE_NAME',
                'soa_item.ITEM_NAME',
                'soa_item.UNIT_NAME',
                'soa_item.RATE'
            ])
            ->join('soa_item_type', 'soa_item_type.ID', '=', 'TYPE')
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('TYPE', 'asc')
            ->orderBy('LINE', 'asc')
            ->get();

        return $result;
    }
}
