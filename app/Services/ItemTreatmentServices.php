<?php

namespace App\Services;

use App\Models\Items;
use App\Models\ItemTreatment;
use Illuminate\Support\Facades\DB;

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

    
    public function Store(int $LOCATION_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, int $NO_OF_USED, bool $IS_AUTO): int
    {
        $ID =  (int) $this->object->ObjectNextID('ITEM_TREATMENT');
        ItemTreatment::create([
            'ID'                => $ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'ITEM_ID'           => $ITEM_ID,
            'QUANTITY'          => $QUANTITY,
            'UNIT_ID'           => $UNIT_ID > 0 ? $UNIT_ID : null,
            'NO_OF_USED'        => $NO_OF_USED,
            'INACTIVE'          => false,
            'IS_AUTO'           => $IS_AUTO
        ]);

        return $ID;
    }

    public function Update(int $ID, int $LOCATION_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, int $NO_OF_USED, bool $INACTIVE, bool $IS_AUTO)
    {
        ItemTreatment::where('ID', $ID)
            ->update([
                'LOCATION_ID'       => $LOCATION_ID,
                'ITEM_ID'           => $ITEM_ID,
                'QUANTITY'          => $QUANTITY,
                'UNIT_ID'           => $UNIT_ID > 0 ? $UNIT_ID : null,
                'NO_OF_USED'        => $NO_OF_USED,
                'INACTIVE'          => $INACTIVE,
                'IS_AUTO'           => $IS_AUTO
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
                'item_treatment.INACTIVE',
                'item_treatment.QUANTITY',
                'item_treatment.IS_AUTO'
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


    public function SearchHemo($search, int $locationId, int $hemoId)
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
            ->whereNotExists(function ($query) use (&$hemoId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis_items as hi')
                    ->whereRaw('hi.ITEM_ID = i.ID')
                    ->where('hi.HEMO_ID', $hemoId);
            })
            ->orderBy('item_treatment.ID', 'desc')
            ->get();

        return $result;
    }



    public function getItemList(bool $isCode, int $locationId)
    {

        if ($isCode) {

            return Items::query()
                ->select(['item.ID', 'item.CODE'])
                ->join('item_treatment as t', 't.ITEM_ID', '=', 'item.ID')
                ->where('item.INACTIVE', '0')
                ->where('t.INACTIVE', '0')
                ->where('t.LOCATION_ID', $locationId)
                ->whereIn('item.TYPE', ['0', '1'])
                ->get();
        }

        return Items::query()
            ->select(['item.ID', 'item.DESCRIPTION'])
            ->join('item_treatment as t', 't.ITEM_ID', '=', 'item.ID')
            ->where('item.INACTIVE', '0')
            ->where('t.INACTIVE', '0')
            ->where('t.LOCATION_ID', $locationId)
            ->whereIn('item.TYPE', ['0', '1'])
            ->get();
    }
}
