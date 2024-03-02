<?php

namespace App\Services;

use App\Models\Locations;

class LocationServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function getList(): object
    {
        return Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
    }
    public function Store(string $NAME, bool $INACTIVE, int $PRICE_LEVEL_ID, int $GROUP_ID): int
    {
        $ID = $this->object->ObjectNextID('LOCATION');

        Locations::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null
        ]);

        return $ID;
    }

    public function Update(int $ID, string $NAME, bool $INACTIVE, int $PRICE_LEVEL_ID, int $GROUP_ID): void
    {

        Locations::where('ID', $ID)->update([
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null
        ]);
    }

    public function Delete(int $ID): void
    {
        Locations::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return Locations::query()
            ->select(
                [
                    'location.ID',
                    'location.NAME',
                    'location.INACTIVE',
                    'location.PRICE_LEVEL_ID',
                    'location.GROUP_ID',
                    'price_level.DESCRIPTION as PRICEL_LEVEL',
                    'item_group.DESCRIPTION as ITEM_GROUP'
                ]
            )
            ->leftJoin('price_level', 'price_level.ID', '=', 'location.PRICE_LEVEL_ID')
            ->leftJoin('item_group', 'item_group.ID', '=', 'location.GROUP_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where('location.NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('location.ID', 'desc')
            ->get();
    }
}
