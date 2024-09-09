<?php

namespace App\Services;

use App\Models\DoctorOrderDefault;

class DoctorOrderDefaultServices
{



    public function getListByLocation(int $LOCATION_ID)
    {
        $result =  DoctorOrderDefault::query()
            ->select([
                'ID',
                'DESCRIPTION',
                'MODIFY'
            ])
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}
