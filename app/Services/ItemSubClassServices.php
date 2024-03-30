<?php

namespace App\Services;

use App\Models\ItemSubClass;

class ItemSubClassServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function GetClassDesc($SUB_CLASS_ID)
    {
        if ($SUB_CLASS_ID) {
            $result = ItemSubClass::select(['item_class.DESCRIPTION as CLASS'])
                ->join('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
                ->where('item_sub_class.ID', $SUB_CLASS_ID)
                ->first()
                ->CLASS;

            return $result;
        }
        return '';
    }

    public function Store(string $CODE, string $DESCRIPTION, int $CLASS_ID): int
    {
        $ID = $this->object->ObjectNextID('ITEM_SUB_CLASS');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('ITEM_SUB_CLASS');

        ItemSubClass::create([
            'ID' => $ID,
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DESCRIPTION' => $DESCRIPTION,
            'CLASS_ID' => $CLASS_ID
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION, int $CLASS_ID): void
    {

        ItemSubClass::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DESCRIPTION' => $DESCRIPTION,
            'CLASS_ID' => $CLASS_ID
        ]);


    }

    public function Delete(int $ID): void
    {
        ItemSubClass::where('ID', $ID)->delete();

    }

    public function Search($search)
    {
        if (!$search) {
            return ItemSubClass::query()->select([
                'item_sub_class.ID',
                'item_sub_class.CODE',
                'item_sub_class.DESCRIPTION',
                'item_class.DESCRIPTION as CLASS'
            ])
                ->join('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
                ->orderBy('item_sub_class.ID', 'desc')
                ->get();
        } else {
            return ItemSubClass::query()->select([
                'item_sub_class.ID',
                'item_sub_class.CODE',
                'item_sub_class.DESCRIPTION',
                'item_class.DESCRIPTION as CLASS'
            ])
                ->join('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
                ->where('item_sub_class.CODE', 'like', '%' . $search . '%')
                ->orWhere('item_sub_class.DESCRIPTION', 'like', '%' . $search . '%')
                ->orWhere('item_class.DESCRIPTION', 'like', '%' . $search . '%')
                ->orderBy('item_sub_class.ID', 'desc')
                ->get();
        }
    }
}
