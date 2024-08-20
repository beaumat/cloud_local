<?php

namespace App\Exports;

use App\Services\ItemServices;
use App\Services\LocationServices;
use Maatwebsite\Excel\Concerns\FromCollection;

class InventoryReportExport implements FromCollection
{   
    
    public function __construct(ItemServices $itemServices, LocationServices $locationServices) 
    {

    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
            
    }
}
