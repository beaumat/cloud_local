<?php

namespace App\Services;
use App\Models\Manufacturers;

class ManufacturerServices
{
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }

    public function Store(string $CODE, string $NAME): int
    {
        $ID = $this->object->ObjectNextID('MANUFACTURER');

        Manufacturers::create([
            'ID' => $ID,
            'CODE' => $CODE,
            'NAME' => $NAME
        ]);

        return $ID;
    }

    public function Update(int $ID, string $CODE, string $NAME): void
    {

        Manufacturers::where('ID', $ID)->update([
            'CODE' => $CODE,
            'NAME' => $NAME
        ]);
    }

    public function Delete(int $ID): void
    {
        Manufacturers::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        if (!$search) {
            return Manufacturers::orderBy('ID', 'desc')->get();
        } else {
            return Manufacturers::where('CODE', 'like', '%' . $search . '%')
                ->orWhere('NAME', 'like', '%' . $search . '%')
                ->orderBy('ID', 'desc')
                ->get();
        }
    }
}
