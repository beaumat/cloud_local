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

    public function Store(int $ITEM_ID, int $SOA_ITEM_ID)
    {

        $ID = $this->object->ObjectNextID('ITEM_SOA_ITEMIZED');

        ItemSoaItemized::create(
            [
                'ID' => $ID,
                'ITEM_ID' => $SOA_ITEM_ID,
                'INACTIVE' => false
            ]
        );

        return $ID;
    }
    public function Update()
    {

    }
    public function Delete()
    {

    }
}