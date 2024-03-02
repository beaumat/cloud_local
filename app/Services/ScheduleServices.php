<?php

namespace App\Services;

use App\Models\Schedules;

class ScheduleServices
{
    public function get($ContactId, $Date, int $LOCATION_ID)
    {
        try {
            return Schedules::where('CONTACT_ID', $ContactId)
                ->where('SCHED_DATE', $Date)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->first();

        } catch (\Throwable $th) {
            return null;
        }

    }
    public function Delete(int $ID, int $LOCATION_ID)
    {
        Schedules::where('id', $ID)->where('LOCATION_ID', $LOCATION_ID)->delete();
    }
    public function Update(int $CONTACT_ID, string $date, int $SHIFT_ID, int $STATUS, $LOG, int $LOCATION_ID)
    {
        Schedules::where('CONTACT_ID', $CONTACT_ID)
            ->where('SCHED_DATE', $date)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'SHIFT_ID' => $SHIFT_ID,
                'SCHED_STATUS' => $STATUS,
                'STATUS_LOG' => $LOG
            ]);
    }
    public function Store(int $SHIFT_ID, int $CONTACT_ID, string $Date, int $STATUS, $LOG, int $LOCATION_ID)
    {
        Schedules::create([
            'SHIFT_ID' => $SHIFT_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'SCHED_DATE' => $Date,
            'SCHED_STATUS' => $STATUS,
            'STATUS_LOG' => $LOG,
            'LOCATION_ID' => $LOCATION_ID
        ]);
    }

}