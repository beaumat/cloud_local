<?php

namespace App\Services;

use App\Models\PriceLevelLines;

class PriceLevelLineServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(int $PRICE_LEVEL_ID, int  $ITEM_ID, float $CUSTOM_PRICE): int
    {
        $ID = $this->object->ObjectNextID('PRICE_LEVEL_LINES');
        PriceLevelLines::create([
            'ID'                => $ID,
            'PRICE_LEVEL_ID'    => $PRICE_LEVEL_ID,
            'ITEM_ID'           => $ITEM_ID,
            'CUSTOM_PRICE'      => $CUSTOM_PRICE
        ]);

        return $ID;
    }


    public function Update(int $ID, string $CUSTOM_PRICE): void
    {
        PriceLevelLines::where('ID', $ID)->update([
            'CUSTOM_PRICE' => $CUSTOM_PRICE
        ]);
    }

    public function Delete(int $ID): void
    {
        PriceLevelLines::where('ID', $ID)->delete();
    }
    public function Search($search, int $PRICE_LEVEL_ID)
    {
        if (!$search) {
            return PriceLevelLines::query()
                ->select([
                    'price_level_lines.ID',
                    'price_level_lines.PRICE_LEVEL_ID',
                    'price_level_lines.ITEM_ID',
                    'price_level_lines.CUSTOM_PRICE',
                    'item.CODE',
                    'item.DESCRIPTION',
                    'item.RATE'
                ])
                ->join('item', 'item.ID', '=', 'price_level_lines.ITEM_ID')
                ->where('price_level_lines.PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                ->orderBy('price_level_lines.ID', 'desc')->get();
        } else {
            return PriceLevelLines::query()
                ->select([
                    'price_level_lines.ID',
                    'price_level_lines.PRICE_LEVEL_ID',
                    'price_level_lines.ITEM_ID',
                    'price_level_lines.CUSTOM_PRICE',
                    'item.CODE',
                    'item.DESCRIPTION',
                    'item.RATE'
                ])
                ->join('item', 'item.ID', '=', 'price_level_lines.ITEM_ID')
                ->where('price_level_lines.PRICE_LEVEL_ID', '=', $PRICE_LEVEL_ID)
                ->where(function ($query) use ($search) {
                    $query->where('item.CODE', 'like', '%' . $search . '%')
                        ->orWhere('price_level_lines.CUSTOM_PRICE', 'like', '%' . $search . '%')
                        ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%');
                })
                ->orderBy('price_level_lines.ID', 'asc')
                ->get();
        }
    }

    public function LoadPriceLevelByItem(int $itemId)
    {
        $result = PriceLevelLines::query()
            ->select(
                [
                    'price_level_lines.ID',
                    'price_level.DESCRIPTION',
                    'price_level_lines.CUSTOM_PRICE',
                    'price_level_lines.CUSTOM_COST'
                ]
            )
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->where('price_level_lines.ITEM_ID', '=', $itemId)
            ->where('price_level.INACTIVE', '=', '0')
            ->where('price_level.TYPE', '=', '1')
            ->get();

        return $result;
    }

    public function DataExists(int $ITEM_ID, int $LOCATION_ID)
    {

        $data =  PriceLevelLines::query()
            ->select('price_level_lines.ID')
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->join('LOCATION as l', 'l.PRICE_LEVEL_ID', '=', 'price_level.ID')
            ->where('price_level_lines.ITEM_ID', $ITEM_ID)
            ->where('l.ID', $LOCATION_ID)
            ->first();

        if($data) {
            return (int) $data->ID;
        }

        return 0;
    }
    public function PriceExists(int $ITEM_ID, int $LOCATION_ID):float
    {

        $data =  PriceLevelLines::query()
            ->select('price_level_lines.CUSTOM_PRICE')
            ->join('price_level', 'price_level.ID', '=', 'price_level_lines.PRICE_LEVEL_ID')
            ->join('LOCATION as l', 'l.PRICE_LEVEL_ID', '=', 'price_level.ID')
            ->where('price_level_lines.ITEM_ID', $ITEM_ID)
            ->where('l.ID', $LOCATION_ID)
            ->first();

        if($data) {
            return (float) $data->CUSTOM_PRICE ?? 0;
        }

        return 0;
    }
}
