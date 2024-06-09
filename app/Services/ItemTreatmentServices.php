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
    public function Store(int $LOCATION_ID, int $ITEM_ID, int $UNIT_ID, int $NO_OF_USED)
    {
        $ID = $this->object->ObjectNextID('ITEM_TREATMENT');
        ItemTreatment::create([
            'ID' => $ID,
            'LOCATION_ID' => $LOCATION_ID,
            'ITEM_ID' => $ITEM_ID,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'NO_OF_USED' => $NO_OF_USED,
            'INACTIVE' => 0
        ]);
    }

    public function Update(int $ID, int $LOCATION_ID, int $ITEM_ID, int $UNIT_ID, int $NO_OF_USED, bool $INACTIVE)
    {
        ItemTreatment::where('ID', $ID)
            ->update([
                'LOCATION_ID' => $LOCATION_ID,
                'ITEM_ID' => $ITEM_ID,
                'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
                'NO_OF_USED' => $NO_OF_USED,
                'INACTIVE' =>  $INACTIVE
            ]);
    }

    public function Delete(int $ID)
    {
        ItemTreatment::where('ID', $ID)->delete();
    }

    public function Search($search)
    {

        if (!$search) {
            return ItemTreatment::query()
                ->select([
                    'item_treatment.ID',
                    'item_class.DESCRIPTION as CLASS'
                ])
                ->join('location as l', 'l.ID', '=', 'item_treatment.LOCATION_ID')
                ->join('item as i', 'item.ID', '=', 'item_treatment.ITEM_ID')
                ->leftJoin('unit_of_measure as u', 'u.ID', 'item_treatment.UNIT_ID')
                ->orderBy('item_sub_class.ID', 'desc')
                ->get();
        } else {
            // return ItemSubClass::query()
            //     ->select([
            //         'item_sub_class.ID',
            //         'item_sub_class.CODE',
            //         'item_sub_class.DESCRIPTION',
            //         'item_class.DESCRIPTION as CLASS'
            //     ])
            //     ->join('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
            //     ->where('item_sub_class.CODE', 'like', '%' . $search . '%')
            //     ->orWhere('item_sub_class.DESCRIPTION', 'like', '%' . $search . '%')
            //     ->orWhere('item_class.DESCRIPTION', 'like', '%' . $search . '%')
            //     ->orderBy('item_sub_class.ID', 'desc')
            //     ->get();
        }
    }
}
