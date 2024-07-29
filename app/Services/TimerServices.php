<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimerServices
{
    private $scheduleServices;
    private $hemoServices;
    private $dateServices;
    private $documentTypeServices;
    private $itemInventoryServices;
    function __construct(
        ScheduleServices $scheduleServices,
        HemoServices $hemoServices,
        DateServices $dateServices,
        DocumentTypeServices $documentTypeServices,
        ItemInventoryServices $itemInventoryServices
    ) {
        $this->scheduleServices = $scheduleServices;
        $this->hemoServices = $hemoServices;
        $this->dateServices = $dateServices;
        $this->documentTypeServices = $documentTypeServices;
        $this->itemInventoryServices = $itemInventoryServices;
    }

    public function getExecute()
    {
        // $ php artisan schedule:work = must run per minute

        $schedlist = $this->scheduleServices->getWaitingList($this->dateServices->NowDate());

        foreach ($schedlist as $sched) {
            $this->getPosted($sched->CONTACT_ID, $sched->SCHED_DATE, $sched->LOCATION_ID);
        }
    }

    private function getPosted(int $CONTACT_ID, string $DATE, int  $LOCATION_ID)
    {
        try {

            $data = $this->hemoServices->getTreatmentID($CONTACT_ID, $DATE, $LOCATION_ID);

            $ID         = (int) $data['ID']; //HEMO_ID
            $TIME_START =       empty($data['TIME_START']) ?  null :  $data['TIME_START'];
            $TIME_END   =       empty($data['TIME_END'])  ?   null : $data['TIME_END'];
            $STATUS_ID  = (int) $data['STATUS_ID'];



            $PRE_WEIGHT             = (int) $data['PRE_WEIGHT'];
            $PRE_BLOOD_PRESSURE     = (int) $data['PRE_BLOOD_PRESSURE'];
            $PRE_BLOOD_PRESSURE2    = (int) $data['PRE_BLOOD_PRESSURE2'];
            $PRE_HEART_RATE         = (int) $data['PRE_HEART_RATE'];
            $PRE_O2_SATURATION      = (int) $data['PRE_O2_SATURATION'];

            $POST_WEIGHT            = (int) $data['POST_WEIGHT'];
            $POST_BLOOD_PRESSURE    = (int) $data['POST_BLOOD_PRESSURE'];
            $POST_BLOOD_PRESSURE2   = (int) $data['POST_BLOOD_PRESSURE2'];
            $POST_HEART_RATE        = (int) $data['POST_HEART_RATE'];
            $POST_O2_SATURATION     = (int) $data['POST_O2_SATURATION'];


            DB::beginTransaction();
            if ($ID > 0) {

                if ($PRE_WEIGHT == 0 || $PRE_BLOOD_PRESSURE == 0 || $PRE_BLOOD_PRESSURE2 == 0 || $PRE_HEART_RATE == 0 || $PRE_O2_SATURATION == 0) {
                    $this->hemoServices->StatusUpdate($ID, 3); // VOID
                    $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 2); // ABSENT
                    DB::commit();
                    return;
                }

                if ($STATUS_ID == 1) {
                    if (!$this->ItemInventory($ID, $DATE, $LOCATION_ID)) {
                        DB::rollBack();
                        return;
                    }
                }

                $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 1); //PRESENT

                if ($POST_WEIGHT == 0 || $POST_BLOOD_PRESSURE == 0 || $POST_BLOOD_PRESSURE2 == 0 || $POST_HEART_RATE == 0 || $POST_O2_SATURATION == 0 || empty($TIME_START) == true ||  empty($TIME_END) == true) {
                    $this->hemoServices->StatusUpdate($ID, 4); // UNPOSTED
                } else {
                    $this->hemoServices->StatusUpdate($ID, 2); // POSTED
                }

                DB::commit();
            } else {
                $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 3); // CANCELLED
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error executing Schedule executed in getPosted: ' . $e->getMessage() . '[' . $CONTACT_ID . ',' . $LOCATION_ID . ', ' . $DATE . ']');
        }
    }
    private function ItemInventory(int $HEMO_ID, string $DATE, int $LOCATION_ID): bool
    {
        try {
            $SOURCE_REF_TYPE = (int) $this->documentTypeServices->getId('Hemodialysis');
            $data = $this->hemoServices->ItemInventory($HEMO_ID);
            if ($data) {
                $this->itemInventoryServices->InventoryExecute($data, $LOCATION_ID, $SOURCE_REF_TYPE, $DATE, false);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
