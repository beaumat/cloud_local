<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\MedcertSchedule;

class MedCertServices
{

    public function GetList(): object
    {
        $result = MedcertSchedule::get();

        return $result;
    }
    public function GetMedcertSchedule(string $id): object
    {
        $result =  MedcertSchedule::where("ID", '=', $id)->first();
        return $result;
    }
    public function UpdatePatientMedCert(int $CONTACT_ID, int  $MED_CERT_SCHED_ID, int $MED_CERT_NURSE_ID)
    {

        Contacts::where('ID', '=', $CONTACT_ID)
            ->where('TYPE', '=', 3)
            ->update([
                'MED_CERT_SCHED_ID' => $MED_CERT_SCHED_ID > 0 ? $MED_CERT_SCHED_ID : null,
                'MED_CERT_NURSE_ID' => $MED_CERT_NURSE_ID > 0 ? $MED_CERT_NURSE_ID : null
            ]);
    }
}
