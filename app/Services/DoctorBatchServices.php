<?php
namespace App\Services;

use App\Models\DoctorBatch;
use App\Models\DoctorBatchPaid;
use Illuminate\Support\Facades\DB;

class DoctorBatchServices
{
    private $object;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices)
    {
        $this->object = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
    }

    public function Get(int $ID)
    {
        $result = DoctorBatch::where('ID', '=', $ID)->first();

        return $result;
    }
    public function Store(int $DOCTOR_ID, int $LOCATION_ID)
    {
        $ID = $this->object->ObjectNextID("DOCTOR_BATCH");

        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('DOCTOR_BATCH');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        DoctorBatch::create([
            'ID' => $ID,
            'CODE' => $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DOCTOR_ID' => $DOCTOR_ID,
            'LOCATION_ID' => $LOCATION_ID,
        ]);
    }
    public function Delete(int $ID)
    {
        DoctorBatch::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $LOCATION_ID)
    {
        $result = DoctorBatch::query()
            ->select([
                'doctor_batch.CODE',
                'c.NAME',
                DB::raw("(select count(*) from doctor_batch_paid where doctor_batch_paid.doctor_batch_id = doctor_batch.ID ) as TOTAL_COUNT"),
                DB::raw("(select SUM(p.AMOUNT) from doctor_batch_paid inner join `check` as p on p.ID = doctor_batch_paid.CHECK_ID where doctor_batch_paid.doctor_batch_id = doctor_batch.ID) as TOTAL_AMOUNT"),
            ])
            ->join('contact as c', 'c.ID', 'doctor_batch.DOCTOR_ID')
            ->where('doctor_batch.LOCATION_ID', '=', $LOCATION_ID)

            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('c.NAME', 'like', '%' . $search . '%');
                    $q->orWhere('doctor_batch.CODE', 'like', '%' . $search . '%');
                });
            })
            ->paginate(30);

        return $result;
    }


    public function StorePaid(int $DOCTOR_BATCH_ID, int $PAYMENT_PERIOD_ID, int $CHECK_ID)
    {
        $ID = $this->object->ObjectNextID("DOCTOR_BATCH_PAID");
        DoctorBatchPaid::create([
            'ID' => $ID,
            'PAYMENT_PERIOD_ID' => $PAYMENT_PERIOD_ID,
            'CHECK_ID' => $CHECK_ID,
            'DOCTOR_BATCH_ID' => $DOCTOR_BATCH_ID,
        ]);
    }
    public function DeletePaid(int $ID)
    {
        DoctorBatchPaid::where('ID', '=', $ID)->delete();
    }
    public function PaidList(int $DOCTOR_BATCH_ID)
    {
        $result = DoctorBatchPaid::query()
            ->select([
                'c.NAME as PATIENT_NAME',
                'pb.AMOUNT',

            ])
            ->join('check_bills as pb', 'pb.CHECK_ID', '=', 'doctor_batch_paid.CHECK_ID')
            ->join('philhealth_prof_fee as ppf', 'ppf.BILL_ID', '=', 'pb.BILL_ID')
            ->join('philhealth as ph', 'ph.ID', '=', 'ppf.PHIC_ID')
            ->join('contact as c', 'c.ID', '=', 'ph.CONTACT_ID')
            ->where('doctor_batch_paid.DOCTOR_BATCH_ID', '=', $DOCTOR_BATCH_ID)
            ->get();

        return $result;
    }
}