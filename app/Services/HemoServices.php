<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\Hemodialysis;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    private function getTime(bool $isStart, string $DATE, int $CONTACT_ID, int $LOCATION_ID): string
    {

        try {
            if ($isStart) {
                return Hemodialysis::query()
                    ->select('hemodialysis.TIME_START')
                    ->where('CUSTOMER_ID', $CONTACT_ID)
                    ->where('LOCATION_ID', $LOCATION_ID)
                    ->where('DATE', $DATE)
                    ->where('STATUS_ID', '2')
                    ->first()
                    ->TIME_START;
            }

            return Hemodialysis::query()
                ->select('hemodialysis.TIME_END')
                ->where('CUSTOMER_ID', $CONTACT_ID)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('DATE', $DATE)
                ->where('STATUS_ID', '2')
                ->first()
                ->TIME_END;
        } catch (\Throwable $th) {
            return '';
        }
    }
    public function GetSummary(int $CONTACT_ID, int $LOCATION_ID, string $DATE_START, string $DATE_END)
    {
        $dataList = Hemodialysis::query()
            ->select(['ID', 'DATE'])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS_ID', '2')
            ->whereBetween('DATE', [$DATE_START, $DATE_END])
            ->get();

        return $dataList;
    }
    public function getDateTime(int $CONTACT_ID, int $LOCATION_ID)
    {
        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(DATE) AS first_date, MAX(DATE) AS last_date'))
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS_ID', '2')
            ->first();


        if ($dates) {
            $firstDate = $dates->first_date ?? null;
            $lastDate = $dates->last_date ?? null;

            if ($firstDate != null && $lastDate != null) {
                $firstTime = $this->getTime(true, $firstDate, $CONTACT_ID, $LOCATION_ID);
                $lastTime = $this->getTime(false, $lastDate, $CONTACT_ID, $LOCATION_ID);

                return [
                    'FIRST_DATE' => $firstDate,
                    'FIRST_TIME' => $firstTime,
                    'LAST_DATE' => $lastDate,
                    'LAST_TIME' => $lastTime
                ];
            }
        }


        return [
            'FIRST_DATE' => '',
            'FIRST_TIME' => '',
            'LAST_DATE' => '',
            'LAST_TIME' => ''
        ];
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
                'c.PIN as PHIC_NO',
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
    public function QuickFilterByDateRange(string $DATE_FORM, string $DATE_TO, $LOCATION_ID)
    {
        $result =  Contacts::query()
            ->select(
                'contact.ID',
                'contact.NAME as PATIENT',
                'contact.PIN',
                'p.NAME as PHYSICIAN',
                DB::raw('count(h.ID) as TOTAL_HEMO')
            )
            ->join('hemodialysis as h', 'h.CUSTOMER_ID', '=', 'contact.ID')
            ->leftJoin('contact as p', 'p.ID', '=', 'contact.SALES_REP_ID')
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.STATUS_ID', 2)
            ->whereBetween('h.DATE', [$DATE_FORM, $DATE_TO])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', 'h.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', 'h.LOCATION_ID')
                    ->where('l.DATE', '>', 'h.DATE');
            })
            ->groupBy(['contact.ID', 'contact.NAME', 'contact.PIN', 'p.NAME'])
            ->get();



        return $result;
    }
}
