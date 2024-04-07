<?php

namespace App\Services;

use App\Models\Hemodialysis;
use App\Models\NursesNotes;
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
        ->where('hemodialysis.ID',$ID)
        ->first();
    }
    public function Update(int $ID, $Object)
    {
        Hemodialysis::where('ID', $ID)->update($Object);
    }
    public function updateBoolean(int $ID, string $COLUMN, bool $Value)
    {
        Hemodialysis::where('ID', $ID)->update([$COLUMN => $Value]);
    }
    public function updateNumber(int $ID, string $COLUMN, float $Value)
    {
        Hemodialysis::where('ID', $ID)->update([$COLUMN => $Value ?? 0]);
    }
    public function updateText(int $ID, string $COLUMN, string $Value)
    {
        Hemodialysis::where('ID', $ID)->update([$COLUMN => $Value ?? 0]);
    }
    public function PreSave(string $DATE, string $CODE, int $CUSTOMER_ID, int $LOCATION_ID)
    {
        $ID = (int) $this->object->ObjectNextID('HEMODIALYSIS');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('HEMODIALYSIS');

        Hemodialysis::create([
            'ID' => $ID,
            'RECORDED_ON' => Carbon::now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'STATUS' => 0,
            'STATUS_DATE' => Carbon::now()->format('Y-m-d'),
            'USER_ID' => $this->user->UserId()
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
    public function Store()
    {

    }
    public function Delete(int $id)
    {
        NursesNotes::where('HEMO_ID', $id)->delete();
        Hemodialysis::where('ID', $id)->delete();
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
            ->paginate($perPage);
    }


}