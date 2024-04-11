<?php

namespace App\Services;

use App\Models\Hemodialysis;
use Carbon\Carbon;

class HemoServices
{

    private $object;
    private $user;
    public function __construct(ObjectServices $objectService, UserServices $userServices)
    {
        $this->object = $objectService;
        $this->user = $userServices;
    }
    public function Get(int $ID)
    {
        return Hemodialysis::where('ID', $ID)->first();
    }
    public function GetFirst(int $ID)
    {
        return Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'c.NAME as CONTACT_NAME',
                'c.DATE_OF_BIRTH',
                'c.TAXPAYER_ID as PHIC_NO',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->where('hemodialysis.ID', $ID)
            ->first();
    }
    public function PreSave(string $DATE, string $CODE, int $CUSTOMER_ID, int $LOCATION_ID)
    {
        $NO_OF_TREATMENT = 0;
        $MACHINE_NO = 0;
        $ID = (int) $this->object->ObjectNextID('HEMODIALYSIS');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('HEMODIALYSIS');

        Hemodialysis::create([
            'ID' => $ID,
            'RECORDED_ON' => Carbon::now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'USER_ID' => $this->user->UserId(),
            'NO_OF_TREATMENT' => $NO_OF_TREATMENT,
            'MACHINE_NO' => $MACHINE_NO,
            'STATUS_ID' => 1,
            'STATUS_DATE' => Carbon::now(),
        ]);

        return $ID;

    }
    public function PreUpdate(int $ID, string $DATE, string $CODE, int $CUSTOMER_ID, int $LOCATION_ID)
    {
        Hemodialysis::where('ID', $ID)->update([
            'CODE' => $CODE,
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
        ]);

    }
    public function Update(
        int $ID,
        string $PRE_WEIGHT,
        string $PRE_BLOOD_PRESSURE,
        string $PRE_HEART_RATE,
        string $PRE_O2_SATURATION,
        string $PRE_TEMPERATURE,
        string $POST_WEIGHT,
        string $POST_BLOOD_PRESSURE,
        string $POST_HEART_RATE,
        string $POST_O2_SATURATION,
        string $POST_TEMPERATURE,
        string $TIME_START,
        string $TIME_END

    ) {
        Hemodialysis::where('ID', $ID)->update([

            'PRE_WEIGHT' => $PRE_WEIGHT,
            'PRE_BLOOD_PRESSURE' => $PRE_BLOOD_PRESSURE,
            'PRE_HEART_RATE' => $PRE_HEART_RATE,
            'PRE_O2_SATURATION' => $PRE_O2_SATURATION,
            'PRE_TEMPERATURE' => $PRE_TEMPERATURE,
            'POST_WEIGHT' => $POST_WEIGHT,
            'POST_BLOOD_PRESSURE' => $POST_BLOOD_PRESSURE,
            'POST_HEART_RATE' => $POST_HEART_RATE,
            'POST_O2_SATURATION' => $POST_O2_SATURATION,
            'POST_TEMPERATURE' => $POST_TEMPERATURE,
            'TIME_START' => $TIME_START != "" ? $TIME_START : null,
            'TIME_END' => $TIME_END != "" ? $TIME_END : null

        ]);

        if ($TIME_START != "" && $TIME_END != "") {
            $this->statusUpdate($ID, 2);
        }
    }

    private function statusUpdate(int $ID, int $STATUS)
    {
        // Check if already done
        $STATUS_ID = Hemodialysis::where('ID', $ID)->first()->STATUS_ID;
        if ($STATUS_ID == 1) { // if DRAFT ONLY
            Hemodialysis::where('ID', $ID)->update([
                'STATUS_ID' => $STATUS,
                'STATUS_DATE' => Carbon::now(),
            ]);
        }



    }
    public function Delete(int $id)
    {

        Hemodialysis::where('ID', $id)->delete();
    }
    public function SearchList($search, int $LOCATION_ID)
    {

        return Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'c.NAME as PATIENT_NAME'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->get();
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        return Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->leftJoin('hemo_status as s', 's.ID', '=', 'hemodialysis.STATUS_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->paginate($perPage);
    }


}