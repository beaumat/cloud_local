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
    public function getList()
    {
        return Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
    }
    public function SOA_FORMAT(int $LOCATION_ID)
    {
        $data =  Locations::where('ID', $LOCATION_ID)->first();

        if ($data->PHIC_SOA_FORMAT) {

            return $data->PHIC_SOA_FORMAT ?? 'PrintSoa';
        }

        return "PrintSoa";
    }
    public function getListExcept($ID)
    {
        return Locations::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->where('ID', '<>', $ID)
            ->get();
    }
    public function getPesonel(int $ID)
    {
        $data = Locations::query()
            ->select([
                'location.ID',
                'm.PRINT_NAME_AS as MANAGER_NAME',
                'm.NICKNAME as MANAGER_POSITION',
                'p.PRINT_NAME_AS as PHIC_NAME',
                'p.NICKNAME as PHIC_POSITION'
            ])
            ->leftJoin('contact as m', 'm.ID', '=', 'location.HCI_MANAGER_ID')
            ->leftJoin('contact as p', 'p.ID', '=', 'location.PHIC_INCHARGE_ID')
            ->where('location.ID', $ID)
            ->first();

        return $data;
    }
    public function get(int $ID)
    {
        $result = Locations::where('ID','=', $ID)->first();
        if($result) {
            return $result;
        }

        return [];
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
        string $ZIP_CODE,
        string $REPORT_HEADER_1,
        string $REPORT_HEADER_2,
        string $REPORT_HEADER_3,
        string $PHIC_SOA_FORMAT = 'PrintSoa',
        bool $PHIC_FORM_MODIFY = false
    ): int {
        $ID = $this->object->ObjectNextID('LOCATION');
        Locations::create([
            'ID'                    => $ID,
            'NAME'                  => $NAME,
            'INACTIVE'              => $INACTIVE,
            'PRICE_LEVEL_ID'        => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
            'GROUP_ID'              => $GROUP_ID > 0 ? $GROUP_ID : null,
            'HCI_MANAGER_ID'        => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
            'PHIC_INCHARGE_ID'      => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
            'NAME_OF_BUSINESS'      => strtoupper($NAME_OF_BUSINESS),
            'ACCREDITATION_NO'      => $ACCREDITATION_NO,
            'BLDG_NAME_LOT_BLOCK'   => strtoupper($BLDG_NAME_LOT_BLOCK),
            'STREET_SUB_VALL'       => strtoupper($STREET_SUB_VALL),
            'BRGY_CITY_MUNI'        => strtoupper($BRGY_CITY_MUNI),
            'PROVINCE'              => strtoupper($PROVINCE),
            'ZIP_CODE'              => $ZIP_CODE,
            'REPORT_HEADER_1'       => $REPORT_HEADER_1,
            'REPORT_HEADER_2'       => $REPORT_HEADER_2,
            'REPORT_HEADER_3'       => $REPORT_HEADER_3,
            'PHIC_SOA_FORMAT'       => $PHIC_SOA_FORMAT,
            'PHIC_FORM_MODIFY'      => $PHIC_FORM_MODIFY

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
        string $ZIP_CODE,
        string $REPORT_HEADER_1,
        string $REPORT_HEADER_2,
        string $REPORT_HEADER_3,
        string $PHIC_SOA_FORMAT,
        bool $PHIC_FORM_MODIFY
    ): void {

        Locations::where('ID', $ID)
            ->update([
                'NAME'                  => $NAME,
                'INACTIVE'              => $INACTIVE,
                'PRICE_LEVEL_ID'        => $PRICE_LEVEL_ID > 0 ? $PRICE_LEVEL_ID : null,
                'GROUP_ID'              => $GROUP_ID > 0 ? $GROUP_ID : null,
                'HCI_MANAGER_ID'        => $HCI_MANAGER_ID > 0 ? $HCI_MANAGER_ID : null,
                'PHIC_INCHARGE_ID'      => $PHIC_INCHARGE_ID > 0 ? $PHIC_INCHARGE_ID : null,
                'NAME_OF_BUSINESS'      => strtoupper($NAME_OF_BUSINESS),
                'ACCREDITATION_NO'      => $ACCREDITATION_NO,
                'BLDG_NAME_LOT_BLOCK'   => strtoupper($BLDG_NAME_LOT_BLOCK),
                'STREET_SUB_VALL'       => strtoupper($STREET_SUB_VALL),
                'BRGY_CITY_MUNI'        => strtoupper($BRGY_CITY_MUNI),
                'PROVINCE'              => strtoupper($PROVINCE),
                'ZIP_CODE'              => $ZIP_CODE,
                'REPORT_HEADER_1'       => $REPORT_HEADER_1,
                'REPORT_HEADER_2'       => $REPORT_HEADER_2,
                'REPORT_HEADER_3'       => $REPORT_HEADER_3,
                'PHIC_SOA_FORMAT'       => $PHIC_SOA_FORMAT,
                'PHIC_FORM_MODIFY'      => $PHIC_FORM_MODIFY

            ]);
    }

    public function Delete(int $LOCATION_ID): void
    {
        Locations::where('ID', $LOCATION_ID)->delete();
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
