<?php

namespace App\Services;

use App\Models\Items;
use App\Models\ItemUnits;
use App\Models\UnitOfMeasures;

class UnitOfMeasureServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(string $NAME, string $SYMBOL, bool $INACTIVE): int
    {
        $ID = $this->object->ObjectNextID('UNIT_OF_MEASURE');

        UnitOfMeasures::create([
            'ID' => $ID,
            'NAME' => $NAME,
            'SYMBOL' => $SYMBOL,
            'INACTIVE' => $INACTIVE
        ]);

        return $ID;
    }

    public function Update(int $ID, string $NAME, string $SYMBOL, bool $INACTIVE): void
    {
        UnitOfMeasures::where('ID', $ID)->update([
            'NAME' => $NAME,
            'SYMBOL' => $SYMBOL,
            'INACTIVE' => $INACTIVE
        ]);
    }

    public function Delete(int $ID): void
    {
        UnitOfMeasures::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return UnitOfMeasures::orderBy('ID', 'desc')->get();
        } else {
            return UnitOfMeasures::where('NAME', 'like', '%' . $search . '%')
                ->orWhere('SYMBOL', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
    public function ItemUnit($ITEM_ID)
    {
        $result = Items::query()
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item.BASE_UNIT_ID')
            ->select(['u.ID', 'u.SYMBOL'])
            ->where('item.ID',  $ITEM_ID)
            ->unionAll(
                ItemUnits::query()
                    ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'item_units.UNIT_ID')
                    ->select(['u.ID', 'u.SYMBOL'])
                    ->where('item_units.ITEM_ID',  $ITEM_ID)
            )
            ->get();

        return $result;
    }
}
