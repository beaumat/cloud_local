<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PatientStatusServices
{

    public function getList(int $month, int $year)
    {


        return DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_ADMISSION) = '$month' and year(contact.DATE_ADMISSION) = '$year' and contact.LOCATION_ID = location.ID ) as `NEW` "),
                DB::raw("(select count(*)  from contact inner join patient_confinement on patient_confinement.patient_id = contact.ID where contact.TYPE=3 and month(patient_confinement.DATE_START) = '$month' and year(patient_confinement.DATE_START) = '$year' and contact.LOCATION_ID = location.ID ) as `CONFINEMENT` "),
                DB::raw("(select count(*)  from contact inner join patient_transfer on patient_transfer.patient_id = contact.ID where contact.TYPE=3 and month(patient_transfer.DATE_TRANSFER) = '$month' and year(patient_transfer.DATE_TRANSFER) = '$year' and contact.LOCATION_ID = location.ID ) as `TRANSFER` "),
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_EXPIRED) = '$month' and year(contact.DATE_EXPIRED) = '$year' and contact.LOCATION_ID = location.ID ) as `EXPIRED` ")


            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }
}