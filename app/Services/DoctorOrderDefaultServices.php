<?php

namespace App\Services;

use App\Models\DoctorOrderDefault;

class DoctorOrderDefaultServices
{

    private $objectServices;
    public function __construct(ObjectServices $objectServices)
    {
        $this->objectServices =   $objectServices;
    }
    private function getMAx(int $LOCATION_ID): int
    {
        $lineNo = (int) DoctorOrderDefault::where('LOCATION_ID', $LOCATION_ID)->max('LINE_NO') ?? 0;

        return $lineNo;
    }
    public function HaveAData(int $LOCATION_ID): bool
    {
        return DoctorOrderDefault::where('LOCATION_ID', $LOCATION_ID)->exists();
    }
    public function Store(int $LOCATION_ID, string $DESCRIPTION)
    {

        $ID = (int) $this->objectServices->ObjectNextID('DOCTOR_ORDER_DEFAULT');
        $LINE_NO = $this->getMAx($LOCATION_ID) +  1;

        DoctorOrderDefault::create([
            'ID'            => $ID,
            'LOCATION_ID'   => $LOCATION_ID,
            'LINE_NO'       => $LINE_NO,
            'DESCRIPTION'   => $DESCRIPTION,
            'INACTIVE'      => false,
            'MODIFY'        => false
        ]);
    }

    public function Update(int $ID, string $DESCRIPTION, bool $INACTIVE, bool $MODIFY)
    {
        DoctorOrderDefault::where('ID', $ID)
            ->update([
                'DESCRIPTION'   => $DESCRIPTION,
                'INACTIVE'      => $INACTIVE,
                'MODIFY'        => $MODIFY
            ]);
    }
    public function Delete(int $ID)
    {
        DoctorOrderDefault::where('ID', $ID)->delete();
    }

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
