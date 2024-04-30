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
    public function getPesonel($LOC_ID)
    {
        return Locations::query()
            ->select([
                'location.ID',
                'm.PRINT_NAME_AS as MANAGER_NAME',
                'm.NICKNAME as MANAGER_POSITION',
                'p.PRINT_NAME_AS as PHIC_NAME',
                'p.NICKNAME as PHIC_POSITION'
            ])
            ->leftJoin('contact as m', 'm.ID', '=', 'location.HCI_MANAGER_ID')
            ->leftJoin('contact as p', 'p.ID', '=', 'location.PHIC_INCHARGE_ID')
            ->where('location.ID', $LOC_ID)
            ->first();
    }
    public function get($LOC_ID)
    {
        return Locations::where('ID', $LOC_ID)->first();
    }
    public function Store(
        string $NAME,
        bool $INACTIVE,
        int $PRICE_LEVEL_ID,
        int $GROUP_ID,
        int $HCI_MANAGER_ID,
        int $PHIC_INCHARGE_ID,
        string $NAME_OF_BUSINESS,
        string $ACCREDITATION_NO,
        string $BLDG_NAME_LOT_BLOCK,
        string $STREET_SUB_VALL,
        string $BRGY_CITY_MUNI,
        string $PROVINCE,
        string $ZIP_CODE
    ): int {
        $ID = $this->object->ObjectNextID('LOCATION');
        Locations::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null,
            'HCI_MANAGER_ID' => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
            'PHIC_INCHARGE_ID' => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
            'NAME_OF_BUSINESS' => $NAME_OF_BUSINESS,
            'ACCREDITATION_NO' => $ACCREDITATION_NO,
            'BLDG_NAME_LOT_BLOCK' => $BLDG_NAME_LOT_BLOCK,
            'STREET_SUB_VALL' => $STREET_SUB_VALL,
            'BRGY_CITY_MUNI' => $BRGY_CITY_MUNI,
            'PROVINCE' => $PROVINCE,
            'ZIP_CODE' => $ZIP_CODE
        ]);

        return $ID;
    }

    public function Update(
        int $ID,
        string $NAME,
        bool $INACTIVE,
        int $PRICE_LEVEL_ID,
        int $GROUP_ID,
        int $HCI_MANAGER_ID,
        int $PHIC_INCHARGE_ID,
        string $NAME_OF_BUSINESS,
        string $ACCREDITATION_NO,
        string $BLDG_NAME_LOT_BLOCK,
        string $STREET_SUB_VALL,
        string $BRGY_CITY_MUNI,
        string $PROVINCE,
        string $ZIP_CODE
    ): void {

        Locations::where('ID', $ID)->update([
            'NAME' => $NAME,
            'INACTIVE' => $INACTIVE,
            'PRICE_LEVEL_ID' => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID' => $GROUP_ID > 0 ? $GROUP_ID : null,
            'HCI_MANAGER_ID' => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
            'PHIC_INCHARGE_ID' => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
            'NAME_OF_BUSINESS' => $NAME_OF_BUSINESS,
            'ACCREDITATION_NO' => $ACCREDITATION_NO,
            'BLDG_NAME_LOT_BLOCK' => $BLDG_NAME_LOT_BLOCK,
            'STREET_SUB_VALL' => $STREET_SUB_VALL,
            'BRGY_CITY_MUNI' => $BRGY_CITY_MUNI,
            'PROVINCE' => $PROVINCE,
            'ZIP_CODE' => $ZIP_CODE
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
