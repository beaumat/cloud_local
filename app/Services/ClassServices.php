<?php
namespace App\Services;

use App\Models\Classes;

class ClassServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function GetList()
    {
        return Classes::query()->select(['ID', 'NAME'])->where('INACTIVE', 0)->get();
    }

}
