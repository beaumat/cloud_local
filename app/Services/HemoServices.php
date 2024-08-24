<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\Hemodialysis;
use App\Models\HemodialysisItems;
use App\Models\ItemSubClass;
use Illuminate\Support\Facades\DB;

class HemoServices
{

    private $object;
    private $user;
    private $systemSettingServices;
    private $dateServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    private $itemServices;
    private $itemInventoryServices;
    public function __construct(ObjectServices $objectService, UserServices $userServices, SystemSettingServices $systemSettingServices, DateServices $dateServices, ItemTreatmentServices $itemTreatmentServices, UnitOfMeasureServices $unitOfMeasureServices, ItemServices $itemServices, ItemInventoryServices $itemInventoryServices)
    {
        $this->object = $objectService;
        $this->user = $userServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
        $this->itemServices = $itemServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }

    public function Get(int $ID)
    {
        $data = Hemodialysis::where('ID', $ID)->first();
        if ($data) {
            return $data;
        }

        return [];
    }
    public function IsNewHemo(int $CONTACT_ID, int $LOCATION_ID, string $DATE): bool
    {
        $count = Hemodialysis::where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '<', $DATE)
            ->where('STATUS_ID', 2)
            ->count();

        if ($count == 0) {
            return true;
        }

        return false;
    }
    public function GetPost(int $CONTACT_ID, int $LOCATION_ID, string $DATE)
    {
        return Hemodialysis::where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->where('STATUS_ID', 2)
            ->first();
    }
    public function GetEmployeeName(int $EMP_ID): string
    {
        $data = Contacts::where('ID', $EMP_ID)->first();

        if ($data) {
            return $data->NAME ?? '';
        }

        return '';
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
    public function GetSummary(int $CONTACT_ID = 0, int $LOCATION_ID = 0, string $DATE_START = '', string $DATE_END = '')
    {
        $dataList = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE'
            ])
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID');
                $join->on('s.DATE', '=', 'hemodialysis.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', 2)
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '2')
            ->whereBetween('hemodialysis.DATE', [$DATE_START, $DATE_END])
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();


        return $dataList;
    }
    public function getDateTime(int $CONTACT_ID, int $LOCATION_ID)
    {
        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(DATE) AS first_date, MAX(DATE) AS last_date'))
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS_ID', 2)
            ->first();

        if ($dates) {
            $firstDate = $dates->first_date ?? null;
            $lastDate = $dates->last_date ?? null;

            if ($firstDate != null && $lastDate != null) {
                $firstTime = $this->getTime(true, $firstDate, $CONTACT_ID, $LOCATION_ID);
                $lastTime = $this->getTime(false, $lastDate, $CONTACT_ID, $LOCATION_ID);

                return [
                    'FIRST_DATE'    => $firstDate,
                    'FIRST_TIME'    => $firstTime,
                    'LAST_DATE'     => $lastDate,
                    'LAST_TIME'     => $lastTime
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
    public function HemoStatus()
    {
        return  DB::table('hemo_status')->select(['ID', 'DESCRIPTION'])->whereNotIn('ID', ['4'])->get();
    }

    public function getDateTimeByRange(int $CONTACT_ID, int $LOCATION_ID, string $DT_FROM, string $DT_TO)
    {
        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(DATE) AS first_date, MAX(DATE) AS last_date'))
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->whereBetween('DATE', [$DT_FROM, $DT_TO])
            ->where('STATUS_ID', 2)
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
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as CONTACT_NAME"),
                'c.DATE_OF_BIRTH',
                'c.PIN as PHIC_NO',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.CUSTOMER_ID',
                'hemodialysis.LOCATION_ID',
                'hemodialysis.SE_DETAILS',
                'hemodialysis.SO_DETAILS',
                'hemodialysis.BFR',
                'hemodialysis.DFR',
                'hemodialysis.DURATION',
                'hemodialysis.DIALYZER',
                'hemodialysis.DIALSATE_N',
                'hemodialysis.DIALSATE_K',
                'hemodialysis.DIALSATE_C'
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
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Hemodialysis::create([
            'ID'                => $ID,
            'RECORDED_ON'       => $this->dateServices->Now(),
            'CODE'              => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'              => $DATE,
            'CUSTOMER_ID'       => $CUSTOMER_ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'USER_ID'           => $this->user->UserId(),
            'NO_OF_TREATMENT'   => $NO_OF_TREATMENT,
            'MACHINE_NO'        => $MACHINE_NO,
            'STATUS_ID'         => 1,
            'STATUS_DATE'       =>  $this->dateServices->Now(),
        ]);

        return $ID;
    }
    private function GetPreviousTreatment(int $HEMO_ID, int $CUSTOMER_ID, string $DATE, int $LOCATION_ID)
    {
        $result = Hemodialysis::where('CUSTOMER_ID', $CUSTOMER_ID)
            ->where('DATE', '<', $DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('ID', '<>', $HEMO_ID)
            ->orderBy('DATE', 'desc')
            ->first();

        return $result;
    }
    public function GetOtherDetailsDefault(int $HEMO_ID, int $CUSTOMER_ID, string $DATE, int $LOCATION_ID)
    {
        //Get Previous  
        $data =  $this->GetPreviousTreatment($HEMO_ID, $CUSTOMER_ID, $DATE, $LOCATION_ID);

        if ($data) {
            Hemodialysis::where('CUSTOMER_ID', $CUSTOMER_ID)
                ->where('DATE', $DATE)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('ID', $HEMO_ID)
                ->update([
                    'SE_DETAILS'        => $data->SE_DETAILS_NEXT ?? null,
                    'SE_DETAILS_NEXT'   => '',
                    'SO_DETAILS'        => $data->ORDER_USE_NEXT == true ? $data->SO_DETAILS ?? null : null,
                    'BFR'               => $data->BFR ?? null,
                    'DFR'               => $data->DFR ?? null,
                    'DURATION'          => $data->DURATION ?? null,
                    'DIALYZER'          => $data->DIALYZER ?? null,
                    'HEPARIN'           => $data->HEPARIN ?? null,
                    'DIALSATE_N'        => $data->DIALSATE_N ?? null,
                    'DIALSATE_K'        => $data->DIALSATE_K ?? null,
                    'DIALSATE_C'        => $data->DIALSATE_C ?? null
                ]);
        }
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
        string $PRE_BLOOD_PRESSURE2,
        string $PRE_HEART_RATE,
        string $PRE_O2_SATURATION,
        string $PRE_TEMPERATURE,
        string $POST_WEIGHT,
        string $POST_BLOOD_PRESSURE,
        string $POST_BLOOD_PRESSURE2,
        string $POST_HEART_RATE,
        string $POST_O2_SATURATION,
        string $POST_TEMPERATURE,
        string $TIME_START,
        string $TIME_END,
        bool $IS_INCOMPLETE
    ) {
        Hemodialysis::where('ID', $ID)
            ->update([
                'PRE_WEIGHT'            => $PRE_WEIGHT,
                'PRE_BLOOD_PRESSURE'    => $PRE_BLOOD_PRESSURE,
                'PRE_BLOOD_PRESSURE2'   => $PRE_BLOOD_PRESSURE2,
                'PRE_HEART_RATE'        => $PRE_HEART_RATE,
                'PRE_O2_SATURATION'     => $PRE_O2_SATURATION,
                'PRE_TEMPERATURE'       => $PRE_TEMPERATURE,
                'POST_WEIGHT'           => $POST_WEIGHT,
                'POST_BLOOD_PRESSURE'   => $POST_BLOOD_PRESSURE,
                'POST_BLOOD_PRESSURE2'  => $POST_BLOOD_PRESSURE2,
                'POST_HEART_RATE'       => $POST_HEART_RATE,
                'POST_O2_SATURATION'    => $POST_O2_SATURATION,
                'POST_TEMPERATURE'      => $POST_TEMPERATURE,
                'TIME_START'            => $TIME_START != "" ? $TIME_START : null,
                'TIME_END'              => $TIME_END != "" ? $TIME_END : null,
                'IS_INCOMPLETE'         => $IS_INCOMPLETE

            ]);
    }
    public function UpdateEmployee(int $ID, int $EMPLOYEE_ID)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'EMPLOYEE_ID'            => $EMPLOYEE_ID > 0 ? $EMPLOYEE_ID : null,
            ]);
    }
    public function SaveOthers(int $ID, string $SE_DETAILS, string $SO_DETAILS, int $BFR, int $DFR, int $DURATION, string $DIALYZER, string  $DIALSATE_N, string $DIALSATE_K, string $DIALSATE_C, bool $DETAILS_USE_NEXT, bool $ORDER_USE_NEXT, string $SE_DETAILS_NEXT, string $HEPARIN)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'SE_DETAILS'        => $SE_DETAILS,
                'SO_DETAILS'        => $SO_DETAILS,
                'BFR'               => $BFR,
                'DFR'               => $DFR,
                'DURATION'          => $DURATION,
                'DIALYZER'          => $DIALYZER,
                'DIALSATE_N'        => $DIALSATE_N,
                'DIALSATE_K'        => $DIALSATE_K,
                'DIALSATE_C'        => $DIALSATE_C,
                'DETAILS_USE_NEXT'  => $DETAILS_USE_NEXT,
                'ORDER_USE_NEXT'    => $ORDER_USE_NEXT,
                'SE_DETAILS_NEXT'   => $SE_DETAILS_NEXT,
                'HEPARIN'           => $HEPARIN
            ]);
    }
    public function UpdatedSpecialOrder(int $ID): bool
    {
        $isBool =  Hemodialysis::where('ID', $ID)->first()->DETAILS_USE_NEXT ?? false;

        if ($isBool) {
            Hemodialysis::where('ID', $ID)
                ->update(['DETAILS_USE_NEXT' => false]);
            return false;
        }

        Hemodialysis::where('ID', $ID)
            ->update(['DETAILS_USE_NEXT' => true]);

        return true;
    }
    public function UpdatedStandingOrder(int $ID): bool
    {
        $isBool =  Hemodialysis::where('ID', $ID)->first()->ORDER_USE_NEXT ?? false;

        if ($isBool) {
            Hemodialysis::where('ID', $ID)->update(['ORDER_USE_NEXT' => false]);
            return false;
        }
        Hemodialysis::where('ID', $ID)->update(['ORDER_USE_NEXT' => true]);
        return true;
    }
    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH
            ]);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        // Check if already done
        Hemodialysis::where('ID', $ID)->update([
            'STATUS_ID' => $STATUS,
            'STATUS_DATE' =>  $this->dateServices->Now(),
        ]);
    }
    public function Delete(int $id)
    {
        HemodialysisItems::where('HEMO_ID', $id)->delete();
        Hemodialysis::where('ID', $id)->delete();
    }
    public function SearchList($search, int $LOCATION_ID)
    {

        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as PATIENT_NAME")
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

        return $result;
    }
    public function SearchListbyShift($search, int $LOCATION_ID, int $SHIFT_ID, string $DATE)
    {

        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as PATIENT_NAME"),
                'sh.NAME as SHIFT'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->join('schedules as s', function ($join) {
                $join->On('s.CONTACT_ID', 'hemodialysis.CUSTOMER_ID');
                $join->On('s.SCHED_DATE', 'hemodialysis.DATE');
                $join->On('s.LOCATION_ID', 'hemodialysis.LOCATION_ID');
            })
            ->join('shift AS sh', 'sh.ID', '=', 's.SHIFT_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($SHIFT_ID > 0, function ($query) use (&$SHIFT_ID) {
                $query->where('s.SHIFT_ID', $SHIFT_ID);
            })
            ->where('hemodialysis.DATE', $DATE)
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->get();

        return $result;
    }
    public function Search($search, int $LOCATION_ID, int $perPage, $DateFrom, $DateTo, int $statusId)
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.STATUS_ID',
                'hemodialysis.FILE_PATH',
                'hemodialysis.IS_INCOMPLETE',
                DB::raw('(SELECT IF(count(sc.ID) > 0,true,false) from service_charges as sc where sc.PATIENT_ID = hemodialysis.CUSTOMER_ID and sc.LOCATION_ID =  hemodialysis.LOCATION_ID and sc.DATE = hemodialysis.DATE ) as IS_SC')
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
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($statusId > 0, function ($query) use (&$statusId) {
                $query->where('hemodialysis.STATUS_ID', $statusId);
            })
            ->whereBetween('hemodialysis.DATE', [$DateFrom, $DateTo])
            ->where('hemodialysis.STATUS_ID', '<>', 4)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->paginate($perPage);
        return $result;
    }

    public function GetUnpostedTreatment()
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.DATE',
                'hemodialysis.CUSTOMER_ID',
                'hemodialysis.LOCATION_ID'
            ])
            ->where('hemodialysis.STATUS_ID', 4)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();

        return $result;
    }
    public function UnpostedTratment(int $LOCATION_ID, $search)
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.STATUS_ID',
                'hemodialysis.FILE_PATH',
                'hemodialysis.IS_INCOMPLETE',
                DB::raw('(SELECT IF(count(sc.ID) > 0,true,false) from service_charges as sc where  sc.PATIENT_ID = hemodialysis.CUSTOMER_ID and sc.LOCATION_ID =  hemodialysis.LOCATION_ID and sc.DATE = hemodialysis.DATE ) as IS_SC')
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
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->where('hemodialysis.STATUS_ID', 4)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();

        return $result;
    }
    public function PatientRecord($search, int $CONTACT_ID, int $perPage)
    {
        return Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.FILE_PATH'
            ])
            ->leftJoin('hemo_status as s', 's.ID', '=', 'hemodialysis.STATUS_ID')
            ->join('location as l', 'l.ID', '=', 'hemodialysis.LOCATION_ID')
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->paginate($perPage);
    }
    public function QuickFilterByDateRange(string $DATE_FORM, string $DATE_TO, $LOCATION_ID, $search)
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT"),
                'contact.PIN',
                DB::raw('count(h.ID) as TOTAL_HEMO'),
                DB::raw('min(h.DATE) as FIRST_DATE'),
                DB::raw('max(h.DATE) as LAST_DATE')

            ])
            ->join('hemodialysis as h', 'h.CUSTOMER_ID', '=', 'contact.ID')
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'h.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'h.LOCATION_ID');
                $join->on('s.DATE', '=', 'h.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', 2)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.STATUS_ID', 2)
            ->whereBetween('h.DATE', [$DATE_FORM, $DATE_TO])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', 'h.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', 'h.LOCATION_ID')
                    ->whereColumn('l.DATE_DISCHARGED', '>=', 'h.DATE');
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('contact.NAME', 'like', '%' . $search . '%');
            })
            ->groupBy(['contact.ID', 'contact.NAME', 'contact.PIN', 'contact.LAST_NAME', 'contact.FIRST_NAME', 'contact.MIDDLE_NAME', 'contact.SALUTATION'])
            ->orderBy('contact.LAST_NAME')
            ->get();



        return $result;
    }
    public function ShowLastTreatment(int $CONTACT_ID, int $LOCATION_ID, string $DATE)
    {
        $result = Hemodialysis::query()
            ->select([
                'PRE_WEIGHT',
                'PRE_BLOOD_PRESSURE',
                'PRE_HEART_RATE',
                'PRE_O2_SATURATION',
                'PRE_TEMPERATURE',
                'POST_WEIGHT',
                'POST_BLOOD_PRESSURE',
                'POST_HEART_RATE',
                'POST_O2_SATURATION',
                'POST_TEMPERATURE',
                'PRE_BLOOD_PRESSURE2',
                'POST_BLOOD_PRESSURE2',
            ])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '<', $DATE)
            ->where('STATUS_ID', 2)
            ->orderBy('DATE', 'desc')
            ->first();

        return $result;
    }
    public function SetIsIncomplete(int $HEMO_ID, $isInCompleted = false)
    {
        Hemodialysis::where('ID', $HEMO_ID)->update(['IS_INCOMPLETE' => $isInCompleted]);
    }
    private function getLine($HEMO_ID): int
    {
        return (int) HemodialysisItems::where('HEMO_ID', $HEMO_ID)->max('LINE_NO');
    }
    private function getGetLastitemNew(int $ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT): string
    {
        $result = HemodialysisItems::query()
            ->select([
                'h.DATE'
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.CUSTOMER_ID', $PATIENT_ID)
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('h.DATE', '<=', $DATE_TREATMENT)
            ->where('h.STATUS_ID', 2)
            ->orderBy('h.DATE', 'desc')
            ->first();

        return $result->DATE  ?? '';
    }
    public function getItemTotalUsed(int $ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT): int
    {
        $newitembyDate = $this->getGetLastitemNew($ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT);

        if (!$newitembyDate) {

            return 0;
        }

        $result_new = HemodialysisItems::query()
            ->select([
                DB::raw('count(*) as total_count')
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.CUSTOMER_ID', $PATIENT_ID)
            ->whereBetween('h.DATE', [$newitembyDate, $DATE_TREATMENT])
            ->first();

        return  (int) $result_new->total_count ?? 0;
    }
    public function ItemStoreExists(int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT): bool
    {
        try {
            $IsExist =  HemodialysisItems::where('HEMO_ID', $HEMO_ID)
                ->where('ITEM_ID', $ITEM_ID)
                ->where('QUANTITY', $QUANTITY)
                ->where('UNIT_ID', $UNIT_ID > 0 ? $UNIT_ID : null)
                ->where('UNIT_BASE_QUANTITY', $UNIT_BASE_QUANTITY)
                ->where('IS_NEW', $IS_NEW)
                ->where('IS_DEFAULT', $IS_DEFAULT)
                ->exists();
        } catch (\Throwable $th) {
            $IsExist = true;
        }

        return $IsExist;
    }
    public function ItemStore(int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT, bool $IS_CASHIER = false, $SC_ITEM_ID = null, $SK_LINE_ID = null): int
    {
        $ID = (int) $this->object->ObjectNextID('HEMODIALYSIS_ITEMS');

        $LINE_NO = (int) $this->getLine($HEMO_ID) + 1;

        HemodialysisItems::create([
            'ID'                    => $ID,
            'HEMO_ID'               => $HEMO_ID,
            'LINE_NO'               => $LINE_NO,
            'ITEM_ID'               => $ITEM_ID,
            'QUANTITY'              => $QUANTITY,
            'UNIT_ID'               => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY'    => $UNIT_BASE_QUANTITY,
            'IS_NEW'                => $IS_NEW,
            'IS_DEFAULT'            => $IS_DEFAULT,
            'IS_POST'               => false,
            'SC_ITEM_ID'            => $SC_ITEM_ID,
            'IS_CASHIER'            => $IS_CASHIER,
            'SK_LINE_ID'            => $SK_LINE_ID

        ]);


        return $ID;
    }
    public function ItemUpdate(int $ID, int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT)
    {

        $itemData =  $this->ItemGet($ID);
        if ($itemData) {
            if ($itemData->IS_POST) {
                $data = $this->Get($HEMO_ID);
                $this->itemInventoryServices->InventoryModify($ITEM_ID, $data->LOCATION_ID, $ID, 27, $data->DATE, 0, $QUANTITY, 0);
            }
        }

        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('IS_DEFAULT', $IS_DEFAULT)
            ->update([
                'QUANTITY'              =>  $QUANTITY,
                'UNIT_ID'               =>  $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY'    =>  $UNIT_BASE_QUANTITY,
                'IS_NEW'                =>  $IS_NEW,
            ]);
    }
    public function ItemUpdateSC_ITEM_ID(int $ID, int $HEMO_ID, int $ITEM_ID, int $SC_ITEM_ID)
    {
        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'SC_ITEM_ID' => $SC_ITEM_ID
            ]);
    }
    public function ItemDelete(int $ID, int $HEMO_ID, int $ITEM_ID, bool $IS_DEFAULT)
    {
        $itemData =  $this->ItemGet($ID);
        if ($itemData) {
            if ($itemData->IS_POST) {
                $data = $this->Get($HEMO_ID);
                $this->itemInventoryServices->DeleteInv($ITEM_ID, $data->LOCATION_ID, 27, $ID, $data->DATE);
            }
        }

        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('IS_DEFAULT', $IS_DEFAULT)
            ->delete();
    }

    public function ItemDeleteTrigger(int $ID, int $HEMO_ID)
    {
        HemodialysisItems::where('SK_LINE_ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->delete();
    }
    public function ItemDelete2(int $HEMO_ID, int $ITEM_ID, int $UNIT_ID, bool $IS_DEFAULT)
    {
        HemodialysisItems::where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('UNIT_ID', $UNIT_ID)
            ->where('IS_DEFAULT', $IS_DEFAULT)
            ->delete();
    }
    public function ItemGet(int $ID)
    {
        $result = HemodialysisItems::where('ID', $ID)->first();

        return $result;
    }
    public function ItemView(int $HEMO_ID)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_ID',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'hemodialysis_items.IS_NEW',
                'hemodialysis_items.IS_DEFAULT',
                'hemodialysis_items.IS_CASHIER',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                't.NO_OF_USED',
                'c.DESCRIPTION as CLASS_NAME'

            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->leftJoin('item_group as g', 'g.ID', '=', 'item.GROUP_ID')
            ->leftJoin('item_sub_class as s', 's.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 's.CLASS_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'hemodialysis_items.UNIT_ID')
            ->leftJoin('item_treatment as t', function ($q) {
                $q->on('t.ITEM_ID', '=', 'hemodialysis_items.ITEM_ID');
                $q->on('t.LOCATION_ID', '=', 'h.LOCATION_ID');
            })
            ->where('hemodialysis_items.HEMO_ID', $HEMO_ID)
            ->get();

        return $result;
    }
    public function CountItems(int $HEMO_ID): int
    {
        return (int) HemodialysisItems::where('HEMO_ID', $HEMO_ID)->count();
    }
    public function ItemInventory(int $ID)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'item.COST'
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('hemodialysis_items.HEMO_ID', $ID)
            ->where('hemodialysis_items.IS_NEW', 1)
            ->get();

        return $result;
    }
    public function CallOutItemUnPosted(string $DATE)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.HEMO_ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'item.COST',
                'hemodialysis.DATE',
                'hemodialysis.LOCATION_ID',
                'hemodialysis.CUSTOMER_ID'
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('item.HEMO_NON_INVENTORY', 0)
            ->whereIn('hemodialysis.STATUS_ID', ['2', '4'])
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('hemodialysis_items.IS_POST', false)
            ->where('hemodialysis.DATE', '<=', $DATE)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();

        return $result;
    }
    public function CallOutItemToBePosted(string $DATE)
    {
        HemodialysisItems::join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->whereIn('hemodialysis.STATUS_ID', ['2', '4'])
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('hemodialysis_items.IS_POST', false)
            ->where('hemodialysis.DATE', '<=', $DATE)
            ->update([
                'hemodialysis_items.IS_POST' => true
            ]);
    }
    public function UsageHistory(int $ITEM_ID, int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {

        $result = HemodialysisItems::query()
            ->select([
                'h.DATE',
                'hemodialysis_items.IS_NEW',
                'hemodialysis_items.QUANTITY'
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.CUSTOMER_ID', $CONTACT_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.DATE', '<=', $DATE)
            ->where('h.STATUS_ID', 2)
            ->orderBy('h.DATE', 'asc')
            ->get();

        return $result;
    }
    public function getTreatmentID(int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {
        $result = Hemodialysis::query()
            ->select([
                'ID',
                'PRE_WEIGHT',
                'PRE_BLOOD_PRESSURE',
                'PRE_HEART_RATE',
                'PRE_O2_SATURATION',
                'PRE_TEMPERATURE',
                'POST_WEIGHT',
                'POST_BLOOD_PRESSURE',
                'POST_HEART_RATE',
                'POST_O2_SATURATION',
                'POST_TEMPERATURE',
                'PRE_BLOOD_PRESSURE2',
                'POST_BLOOD_PRESSURE2',
                'TIME_START',
                'TIME_END',
                'STATUS_ID',
                'IS_INCOMPLETE'
            ])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->whereBetween('STATUS_ID', [1, 4])
            ->first();



        if ($result) {
            return [
                'ID'                     => (int) $result->ID,
                'PRE_WEIGHT'             => (float) $result->PRE_WEIGHT ?? 0,
                'PRE_BLOOD_PRESSURE'     => (float) $result->PRE_BLOOD_PRESSURE ?? 0,
                'PRE_HEART_RATE'         => (float) $result->PRE_HEART_RATE ?? 0,
                'PRE_O2_SATURATION'      => (float) $result->PRE_O2_SATURATION ?? 0,
                'POST_WEIGHT'            => (float) $result->POST_WEIGHT ?? 0,
                'POST_BLOOD_PRESSURE'    => (float) $result->POST_BLOOD_PRESSURE ?? 0,
                'POST_HEART_RATE'        => (float) $result->POST_HEART_RATE ?? 0,
                'POST_O2_SATURATION'     => (float) $result->POST_O2_SATURATION ?? 0,
                'PRE_BLOOD_PRESSURE2'    => (float) $result->PRE_BLOOD_PRESSURE2 ?? 0,
                'POST_BLOOD_PRESSURE2'   => (float) $result->POST_BLOOD_PRESSURE2 ?? 0,
                'TIME_START'             => $result->TIME_START ?? '',
                'TIME_END'               => $result->TIME_END ?? '',
                'STATUS_ID'              => $result->STATUS_ID ?? 0,
                'IS_INCOMPLETE'         => $result->IS_INCOMPLETE ?? false
            ];
        }

        return [
            'ID'                     => 0,
            'PRE_WEIGHT'             => 0,
            'PRE_BLOOD_PRESSURE'     => 0,
            'PRE_HEART_RATE'         => 0,
            'PRE_O2_SATURATION'      => 0,
            'POST_WEIGHT'            => 0,
            'POST_BLOOD_PRESSURE'    => 0,
            'POST_HEART_RATE'        => 0,
            'POST_O2_SATURATION'     => 0,
            'PRE_BLOOD_PRESSURE2'    => 0,
            'POST_BLOOD_PRESSURE2'   => 0,
            'TIME_START'             => '',
            'TIME_END'               => '',
            'STATUS_ID'              => 0,
            'IS_INCOMPLETE'          => false
        ];
    }

    public function AddItemDefault(int $ItemTreatmentId, $hemoData)
    {
        $data = $this->itemTreatmentServices->Get($ItemTreatmentId); // get item treatment details
        if ($data) {
            $gotNew = true;
  
            $NEW_TREATMENT_QTY = (float) $data->NEW_TREATMENT_QTY ?? 0;
            $QTY = 0;
            if ($NEW_TREATMENT_QTY > 0) {
                $isNew = (bool)  $this->IsNewHemo($hemoData->CUSTOMER_ID, $hemoData->LOCATION_ID, $hemoData->DATE);
                if ($isNew == true) {
                    $QTY = $NEW_TREATMENT_QTY;
                } else {
                    $QTY = (float) $data->QUANTITY;
                }
            } else {
                $QTY = (float) $data->QUANTITY;
            }

            try {
                $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
                $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
                $this->ItemStore($hemoData->ID, $data->ITEM_ID, $QTY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew, true);
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
            }
        }
    }
    public function GetNoTreatment(int $CUSTOMER_ID, int $LOCATION_ID, string $DATE): int
    {
        return (int) Hemodialysis::where('CUSTOMER_ID', $CUSTOMER_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '<=', $DATE)
            ->whereBetween('STATUS_ID', [1, 2])
            ->count();
    }
    public function codeIfExist(string $CODE): bool
    {
        return Hemodialysis::where('CODE', $CODE)->exists();
    }
    public function UpdateQRFile($CODE, $FILE_NAME, $FILE_PATH): bool
    {
        $data =  $this->codeIfExist($CODE);
        if ($data) {
            Hemodialysis::where('CODE', $CODE)
                ->update([
                    'FILE_NAME' => $FILE_NAME,
                    'FILE_PATH' => $FILE_PATH
                ]);

            return true;
        }

        return false;
    }
    public function ItemQuery(int $PATIENT_ID, string $DATE, int $LOCATION_ID, int $ITEM_ID, float $QTY, bool $IS_DELETE, int $UNIT_ID)
    {

        $itemDetails =  $this->itemServices->get($ITEM_ID);
        if ($itemDetails) {
            $hasSubClass =  ItemSubClass::where('ID', $itemDetails->SUB_CLASS_ID)->first();
            if ($hasSubClass) {
                if ($hasSubClass->IN_HEMO == false) {
                    return;
                }
            }
        }

        $dataItem = HemodialysisItems::select([
            'hemodialysis_items.ID',
            'hemodialysis_items.HEMO_ID'
        ])
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis.CUSTOMER_ID', $PATIENT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.DATE', $DATE)
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('hemodialysis_items.IS_DEFAULT', false)
            ->first();

        if ($dataItem) { // HEMO EXISTS
            if ($IS_DELETE) {
                $this->ItemDelete($dataItem->ID, $dataItem->HEMO_ID, $ITEM_ID, false); // deleted
                return;
            }
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($ITEM_ID, $UNIT_ID);
            $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
            $this->ItemUpdate($dataItem->ID, $dataItem->HEMO_ID, $ITEM_ID, $QTY, $UNIT_ID,  $UNIT_BASE_QUANTITY, true, false); // updated
            return;
        }
        // new item
        $hemoData = Hemodialysis::select(['ID'])
            ->where('hemodialysis.CUSTOMER_ID', $PATIENT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.DATE', $DATE)
            ->first();

        if ($hemoData) {
            $unitRelated = $this->unitOfMeasureServices->GetItemUnitDetails($ITEM_ID, $UNIT_ID);
            $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
            $this->ItemStore($hemoData->ID, $ITEM_ID, $QTY, $UNIT_ID, $UNIT_BASE_QUANTITY, true, false); // created
        }
    }

    public function IsRestrictedFromUnposted(string $DATE, int $LOCATION_ID): bool
    {
        return  Hemodialysis::where('DATE', '<', $DATE)
            ->where('STATUS_ID', 4)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->exists();
    }

    public function ItemListWithIsCashier(int $PATIENT_ID, int $LOCATION_ID, string $DATE)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.HEMO_ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_ID',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'i.RATE',
                'i.TAXABLE',
                'i.COGS_ACCOUNT_ID',
                'i.ASSET_ACCOUNT_ID',
                'i.GL_ACCOUNT_ID'
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->join('item as i', 'i.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->where('h.CUSTOMER_ID', $PATIENT_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.DATE', $DATE)
            ->where('IS_CASHIER', true)
            ->orderBy('LINE_NO', 'asc')
            ->get();


        return $result;
    }
}
