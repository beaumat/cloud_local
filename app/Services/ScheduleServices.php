<?php

namespace App\Services;

use App\Models\Schedules;

class ScheduleServices
{
    public function scheduleList($Date, int $LOCATION_ID)
    {
        return Schedules::query()
            ->select([
                'schedules.CONTACT_ID',
                'c.NAME aS CONTACT_NAME',
                's.NAME as SHIFT',
                't.DESCRIPTION as STATUS'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->leftJoin('shift as s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->leftJoin('schedule_status as t', 't.ID', '=', 'schedules.SCHED_STATUS')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $Date)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->orderBy('schedules.SHIFT_ID')
            ->get();
    }
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

    public function ContactSchedule(int $CONTACT_ID, int $LOCATION_ID)
    {

        $result = Schedules::query()
            ->select([
                'schedules.SCHED_DATE',
                'schedules.SHIFT_ID',
                'schedules.SCHED_STATUS',
                's.DESCRIPTION as STATUS',
                't.NAME as SHIFT'
            ])
            ->leftJoin('schedule_status as s', 's.ID', '=', 'schedules.SCHED_STATUS')
            ->leftJoin('shift as t', 't.ID', '=', 'schedules.SHIFT_ID')
            ->where('schedules.CONTACT_ID', $CONTACT_ID)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->orderBy('schedules.SCHED_DATE', 'asc')
            ->get();

        return $result;
    }

}