<?php

namespace App\Services;

use App\Models\ItemSoaItemized;

class ItemSoaItemizedServices
{

    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function itemExist(int $ITEM_ID, int $SOA_ITEM_ID): bool
    {
        return ItemSoaItemized::where('ITEM_ID', '=', $ITEM_ID)->where('SOA_ITEM_ID', '=', $SOA_ITEM_ID)->exists();
    }
    public function Store(int $ITEM_ID, int $SOA_ITEM_ID)
    {

        $ID = (int) $this->object->ObjectNextID('ITEM_SOA_ITEMIZED');

        ItemSoaItemized::create(
            [
                'ID' => $ID,
                'ITEM_ID' => $ITEM_ID,
                'SOA_ITEM_ID' => $SOA_ITEM_ID,
                'INACTIVE' => false
            ]
        );

        return $ID;
    }
    public function Update(int $ID, int $ITEM_ID, int $SOA_ITEM_ID, bool $INACTIVE)
    {

        ItemSoaItemized::where('ID', '=', $ID)->update(
            [
                'ITEM_ID' => $ITEM_ID,
                'SOA_ITEM_ID' => $SOA_ITEM_ID,
                'INACTIVE' => $INACTIVE
            ]
        );
    }
    public function Delete(int $ID)
    {
        ItemSoaItemized::where('ID', '=', $ID)->delete();
    }
    public function Get(int $ID)
    {
        $result = ItemSoaItemized::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
    public function List(int $SOA_ITEM_ID)
    {

        $result = ItemSoaItemized::query()
            ->select([
                'item_soa_itemized.ID',
                'item.CODE',
                'item.DESCRIPTION',
                'item_soa_itemized.INACTIVE'
            ])->join('item', 'item.ID', '=', 'item_soa_itemized.ITEM_ID')
            ->where('item_soa_itemized.SOA_ITEM_ID', '=', $SOA_ITEM_ID)
            ->get();

        return $result;
    }

}