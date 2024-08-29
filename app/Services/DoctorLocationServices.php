<?php

namespace App\Services;

use App\Models\DoctorLocation;

class DoctorLocationServices
{

    public function Store(int $LOCATION_ID, int $DOCTOR_ID)
    {
        DoctorLocation::create([
            'LOCATION_ID'   => $LOCATION_ID,
            'DOCTOR_ID'     => $DOCTOR_ID
        ]);
    }
    public function Delete(int $LOCATION_ID, int $DOCTOR_ID)
    {

        DoctorLocation::where('LOCATION_ID', $LOCATION_ID)->where('DOCTOR_ID', $DOCTOR_ID)->delete();
    }

    public function ViewList(int $LOCATION_ID)
    {
        $result = DoctorLocation::query()
            ->select([
                'ID',
                'c.NAME'
            ])
            ->join('contact as c', 'c.ID', '=', 'doctor_location.DOCTOR_ID')
            ->where('doctor_location.LOCATION_ID', $LOCATION_ID)
            ->where('c.TYPE', 4)
            ->get();

        return $result;
    }
}
