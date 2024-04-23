<?php

namespace App\Services;

use App\Models\PatientDoctor;

class PatientDoctorServices
{

    private $object;

    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function Store(int $PATIENT_ID, int $DOCTOR_ID)
    {
        $ID = (int) $this->object->ObjectNextID('PATIENT_DOCTOR');

        PatientDoctor::create([
            'ID' => $ID,
            'PATIENT_ID' => $PATIENT_ID,
            'DOCTOR_ID' => $DOCTOR_ID
        ]);
    }
    public function GetList(int $id)
    {
        return PatientDoctor::query()
            ->select([
                'patient_doctor.ID',
                'c.PIN',
                'c.NAME'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'patient_doctor.DOCTOR_ID')
            ->where('patient_doctor.PATIENT_ID', $id)
            ->get();
    }
    public function Delete(int $ID)
    {

        PatientDoctor::where('ID', $ID)->delete();
    }
}
