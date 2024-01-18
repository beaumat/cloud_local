<?php

namespace App\Services;

use App\Models\ItemClass;

class ItemClassServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(string $CODE, string $DESCRIPTION): int
    {
        $ID = $this->object->ObjectNextID('ITEM_CLASS');

        ItemClass::create([
            'ID' =>             $ID,
            'CODE' =>           $CODE,
            'DESCRIPTION' =>    $DESCRIPTION
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $DESCRIPTION): void
    {

        ItemClass::where('ID', $ID)->update([
            'CODE' =>           $CODE,
            'DESCRIPTION' =>    $DESCRIPTION
        ]);
    }

    public function Delete(int $ID): void
    {
        ItemClass::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return ItemClass::orderBy('ID', 'desc')->get();
        } else {
            return ItemClass::where('CODE', 'like', '%' . $search . '%')
                ->orWhere('DESCRIPTION', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
}
