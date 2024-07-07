<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\Hemodialysis;
use App\Models\HemodialysisItems;
use Illuminate\Support\Facades\DB;

class HemoServices
{

    private $object;
    private $user;
    private $systemSettingServices;
    private $dateServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    public function __construct(
        ObjectServices $objectService,
        UserServices $userServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        ItemTreatmentServices $itemTreatmentServices,
        UnitOfMeasureServices $unitOfMeasureServices
    ) {
        $this->object = $objectService;
        $this->user = $userServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
        $this->itemTreatmentServices = $itemTreatmentServices;
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }

    public function Get(int $ID)
    {
        return Hemodialysis::where('ID', $ID)->first();
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
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.CUSTOMER_ID',
                'hemodialysis.LOCATION_ID'
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
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'CUSTOMER_ID' => $CUSTOMER_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'USER_ID' => $this->user->UserId(),
            'NO_OF_TREATMENT' => $NO_OF_TREATMENT,
            'MACHINE_NO' => $MACHINE_NO,
            'STATUS_ID' => 1,
            'STATUS_DATE' =>  $this->dateServices->Now(),
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
        string $TIME_END

    ) {
        Hemodialysis::where('ID', $ID)
            ->update([
                'PRE_WEIGHT' => $PRE_WEIGHT,
                'PRE_BLOOD_PRESSURE' => $PRE_BLOOD_PRESSURE,
                'PRE_BLOOD_PRESSURE2' => $PRE_BLOOD_PRESSURE2,
                'PRE_HEART_RATE' => $PRE_HEART_RATE,
                'PRE_O2_SATURATION' => $PRE_O2_SATURATION,
                'PRE_TEMPERATURE' => $PRE_TEMPERATURE,
                'POST_WEIGHT' => $POST_WEIGHT,
                'POST_BLOOD_PRESSURE' => $POST_BLOOD_PRESSURE,
                'POST_BLOOD_PRESSURE2' => $POST_BLOOD_PRESSURE2,
                'POST_HEART_RATE' => $POST_HEART_RATE,
                'POST_O2_SATURATION' => $POST_O2_SATURATION,
                'POST_TEMPERATURE' => $POST_TEMPERATURE,
                'TIME_START' => $TIME_START != "" ? $TIME_START : null,
                'TIME_END' => $TIME_END != "" ? $TIME_END : null

            ]);

        // if ($TIME_START != "" && $TIME_END != "") {
        //     $this->statusUpdate($ID, 2);
        // } else {
        //     $this->statusUpdate($ID, 1);
        // }
    }

    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Hemodialysis::where('ID', $ID)->update([
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

        return $result;
    }
    public function getPrevousEntry(int $LOCATION_ID, string $Date, int $CONTACT_ID)
    {
        return Hemodialysis::where('');
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
            ->orderBy('hemodialysis.DATE', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->paginate($perPage);
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
    public function QuickFilterByDateRange(string $DATE_FORM, string $DATE_TO, $LOCATION_ID)
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME as PATIENT',
                'contact.PIN',
                DB::raw('count(h.ID) as TOTAL_HEMO')

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
                    ->where('l.DATE', '>', 'h.DATE');
            })
            ->groupBy(['contact.ID', 'contact.NAME', 'contact.PIN'])
            ->get();



        return $result;
    }
    public function GetLastTreatment(int $CONTACT_ID, int $LOCATION_ID, string $DATE)
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
            ->where('STATUS_ID', '<>', 0)
            ->orderBy('ID', 'desc')
            ->first();

        return $result;
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
    public function ItemStore(int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW)
    {
        $ID = (int) $this->object->ObjectNextID('HEMODIALYSIS_ITEMS');

        $LINE_NO = (int) $this->getLine($HEMO_ID) + 1;

        HemodialysisItems::create([
            'ID' => $ID,
            'HEMO_ID' => $HEMO_ID,
            'LINE_NO' => $LINE_NO,
            'ITEM_ID' => $ITEM_ID,
            'QUANTITY' => $QUANTITY,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'IS_NEW' => $IS_NEW
        ]);
    }
    public function ItemUpdate(int $ID, int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW)
    {
        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'QUANTITY' => $QUANTITY,
                'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'IS_NEW' => $IS_NEW
            ]);
    }
    public function ItemDelete(int $ID, int $HEMO_ID, int $ITEM_ID)
    {
        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->delete();
    }
    public function ItemDelete2(int $HEMO_ID, int $ITEM_ID, int $UNIT_ID)
    {
        HemodialysisItems::where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('UNIT_ID', $UNIT_ID)
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
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                't.NO_OF_USED'
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
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
                'TIME_START',
                'TIME_END',
                'STATUS_ID'
            ])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->whereBetween('STATUS_ID', [1, 4])
            ->first();



        if ($result) {
            return [
                'ID' =>         $result->ID,
                'TIME_START' => $result->TIME_START,
                'TIME_END' =>   $result->TIME_END,
                'STATUS_ID' =>  $result->STATUS_ID ?? 0
            ];
        }

        return [
            'ID' => 0,
            'TIME_START' => null,
            'TIME_END' => null,
            'STATUS_ID' => 0
        ];
    }

    public function AddItem(int $ItemTreatmentId, $hemoData)
    {
        $data = $this->itemTreatmentServices->Get($ItemTreatmentId); // get item treatment details
        if ($data) {
            $gotNew = true;
            if ($data->NO_OF_USED > 1) {
                if ($hemoData) {
                    $totalused = (int)  $this->getItemTotalUsed($data->ITEM_ID, $hemoData->LOCATION_ID, $hemoData->CUSTOMER_ID, $hemoData->DATE);
                    if ($totalused == 0) {
                        $gotNew = true;
                    } elseif ($totalused < $data->NO_OF_USED) {
                        $gotNew = false;
                    }
                }
            }
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
                $this->ItemStore($hemoData->ID, $data->ITEM_ID, $QTY, $data->UNIT_ID ?? 0, $UNIT_BASE_QUANTITY, $gotNew);
            } catch (\Throwable $th) {
                session()->flash('error', $th->getMessage());
            }
        }
    }
}
