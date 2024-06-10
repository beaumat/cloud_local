<?php

namespace App\Services;

use App\Models\ItemTreatment;

class ItemTreatmentServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Get(int $Id)
    {
        return ItemTreatment::where('ID', $Id)->first();
    }
    public function Store(int $LOCATION_ID, int $ITEM_ID, int $UNIT_ID, int $NO_OF_USED): int
    {
        $ID =  (int) $this->object->ObjectNextID('ITEM_TREATMENT');
        ItemTreatment::create([
            'ID'                => $ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'ITEM_ID'           => $ITEM_ID,
            'UNIT_ID'           => $UNIT_ID > 0 ? $UNIT_ID : null,
            'NO_OF_USED'        => $NO_OF_USED,
            'INACTIVE'          => false
        ]);

        return $ID;
    }

    public function Update(int $ID, int $LOCATION_ID, int $ITEM_ID, int $UNIT_ID, int $NO_OF_USED, bool $INACTIVE)
    {
        ItemTreatment::where('ID', $ID)
            ->update([
                'LOCATION_ID'       => $LOCATION_ID,
                'ITEM_ID'           => $ITEM_ID,
                'UNIT_ID'           => $UNIT_ID > 0 ? $UNIT_ID : null,
                'NO_OF_USED'        => $NO_OF_USED,
                'INACTIVE'          =>  $INACTIVE
            ]);
    }

    public function Delete(int $ID)
    {
        ItemTreatment::where('ID', $ID)->delete();
    }

    public function Search($search, $locationId)
    {
        $result = ItemTreatment::query()
            ->select([
                'item_treatment.ID',
                'l.NAME as LOCATION_NAME',
                'i.DESCRIPTION as ITEM_NAME',
                'u.SYMBOL',
                'item_treatment.NO_OF_USED',
                'item_treatment.INACTIVE'
            ])
            ->join('location as l', 'l.ID', '=', 'item_treatment.LOCATION_ID')
            ->join('item as i', 'i.ID', '=', 'item_treatment.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', 'item_treatment.UNIT_ID')
            ->when($locationId, function ($query) use (&$locationId) {
                $query->where('item_treatment.LOCATION_ID', '=', $locationId);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('l.NAME', 'like', '%' . $search . '%')
                    ->orWhere('i.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('u.NAME', 'like', '%' . $search . '%');
            })
            ->orderBy('item_treatment.ID', 'desc')
            ->get();

        return $result;
    }
}
