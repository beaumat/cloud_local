<?php

namespace App\Services;

use App\Models\ItemUnitPriceLevels;

class ItemUnitPriceLevelServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(int $ITEM_UNIT_LINE_ID, int $PRICE_LEVEL_ID, float $CUSTOM_PRICE)
    {
        $ID = $this->object->ObjectNextID('ITEM_UNIT_PRICE_LEVELS');

        ItemUnitPriceLevels::create([
            'ID'                => $ID,
            'ITEM_UNIT_LINE_ID' => $ITEM_UNIT_LINE_ID,
            'PRICE_LEVEL_ID'    => $PRICE_LEVEL_ID,
            'CUSTOM_PRICE'      => $CUSTOM_PRICE
        ]);
    }
    public function Update(int $ID, float $CUSTOM_PRICE)
    {
        ItemUnitPriceLevels::where('ID', $ID)->update([
            'CUSTOM_PRICE' => $CUSTOM_PRICE
        ]);
    }
    public function Delete(int $ID)
    {
        ItemUnitPriceLevels::where('ID', $ID)->delete();
    }
    public function Search(int $ITEM_UNIT_LINE_ID)
    {
        return ItemUnitPriceLevels::query()
            ->select([
                'item_unit_price_levels.ID',
                'price_level.DESCRIPTION',
                'item_unit_price_levels.CUSTOM_PRICE'
            ])
            ->join('price_level', 'price_level.ID', '=', 'item_unit_price_levels.PRICE_LEVEL_ID')
            ->where('item_unit_price_levels.ITEM_UNIT_LINE_ID', $ITEM_UNIT_LINE_ID)
            ->orderBy('item_unit_price_levels.ID', 'asc')
            ->get();
    }
}
