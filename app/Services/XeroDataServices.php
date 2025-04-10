<?php

namespace App\Services;

use App\Models\XeroData;

class XeroDataServices
{

    public function __construct()
    {

    }



    public function viewData(int $locationId, int $year = 0, int $month = 0)
    {

        $result = XeroData::where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->when($year > 0, function ($query) use (&$year) {
                $query->whereYear('DATE', $year);
            })
            ->when($month > 0, function ($query) use (&$month) {
                $query->whereMonth('DATE', $month);
            })
            ->get();

        return $result;

    }
    public function viewDataPerGroupReference(int $locationId, int $year = 0, int $month = 0) {
        $result = XeroData::where('LOCATION_ID', '=', $locationId)
        ->where('POSTED', '=', 0)
        ->when($year > 0, function ($query) use (&$year) {
            $query->whereYear('DATE', $year);
        })
        ->when($month > 0, function ($query) use (&$month) {
            $query->whereMonth('DATE', $month);
        })
        ->groupBy(['REFERENCE','DATE','SOURCE_TYPE'])
        ->get();

    return $result;
    }
    public function callReference(string $ref)
    {
        $result = XeroData::query()
            ->where('REFERENCE','=', $ref)
            ->orderBy('DATE')
            ->orderBy('SOURCE_TYPE')
            ->orderBy('REFERENCE')
            ->get();

        return $result;
    }
    public function RunData($data)
    {



    }

}