<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimerServices
{
    private $scheduleServices;
    private $hemoServices;
    private $dateServices;
    private $itemInventoryServices;
    private $serviceChargeServices;
    private $userServices;
    function __construct(
        ScheduleServices $scheduleServices,
        HemoServices $hemoServices,
        DateServices $dateServices,
        ItemInventoryServices $itemInventoryServices,
        ServiceChargeServices $serviceChargeServices,
        UserServices $userServices

    ) {
        $this->scheduleServices      = $scheduleServices;
        $this->hemoServices          = $hemoServices;
        $this->dateServices          = $dateServices;
        $this->itemInventoryServices = $itemInventoryServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->userServices          = $userServices;
    }
    private function generateUnposted()
    {
        // $ php artisan schedule:work = must run per minute
        $unPostList = $this->hemoServices->GetUnpostedTreatment();
        foreach ($unPostList as $list) {
            $this->getPosted($list->CUSTOMER_ID, $list->DATE, $list->LOCATION_ID);
        }
    }
    private function generateWaitingList($transDate)
    {

        $schedlist = $this->scheduleServices->getWaitingList($transDate);
        foreach ($schedlist as $sched) {
            $this->getPosted($sched->CONTACT_ID, $sched->SCHED_DATE, $sched->LOCATION_ID);
        }
    }
    private function generateItemHemo($transDate)
    {

        $itemData = $this->hemoServices->CallOutItemUnPosted($transDate);

        foreach ($itemData as $list) {
            $this->updateUnpostedItemOnly($list->HEMO_ID);
            Log::warning('Done Hemo ID:' . $list->HEMO_ID . ' - DATE:' . $list->DATE . '- LOCATION_ID:' . $list->LOCATION_ID);
        }

    }
    private function updateUnpostedItemOnly(int $HEMO_ID)
    {
        DB::beginTransaction();
        try {
            $this->hemoServices->getMakeJournal($HEMO_ID);
            $this->hemoServices->makeItemInventory($HEMO_ID);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error executing generateItem() : ' . $th->getMessage());
        }
    }
    private function GenerateItemServiceCharges($transDate)
    {

        DB::beginTransaction();
        try {
            $SOURCE_REF_TYPE = 29;
            $itemData        = $this->serviceChargeServices->GetWalkInServiceChargeTransaction($transDate);
            foreach ($itemData as $list) {
                $QTY = (float) ($list->QUANTITY * $list->UNIT_BASE_QUANTITY ?? 1) * -1;
                $this->itemInventoryServices->InventoryModify(
                    $list->ITEM_ID,
                    $list->LOCATION_ID,
                    $list->ID,
                    $SOURCE_REF_TYPE,
                    $list->DATE,
                    0,
                    $QTY,
                    $list->COST ?? 0
                );
            }
            $this->serviceChargeServices->GetWalkInServiceChargePosted($transDate); // to update update
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Error executing SC generateItem() : ' . $th->getMessage());
        }
    }
    public function getExecute()
    {
        $transDate = $this->dateServices->NowDate();
       // $transDate = $this->dateServices->BackDate();

        $this->generateUnposted();
        $this->generateWaitingList($transDate);
        $this->GenerateItemServiceCharges($transDate);
        $this->generateItemHemo($transDate);
        $this->userDefaultUserDate();
    }

    public function getPosted(int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {
        try {

            $data = $this->hemoServices->getTreatmentID($CONTACT_ID, $DATE, $LOCATION_ID);

            $ID         = (int) $data['ID']; //HEMO_ID
            $TIME_START = empty($data['TIME_START']) ? null : $data['TIME_START'];
            $TIME_END   = empty($data['TIME_END']) ? null : $data['TIME_END'];
            $STATUS_ID  = (int) $data['STATUS_ID'];
            $IS_PF      = (bool) $data['IS_PF'];

            $PRE_WEIGHT          = (int) $data['PRE_WEIGHT'];
            $PRE_BLOOD_PRESSURE  = (int) $data['PRE_BLOOD_PRESSURE'];
            $PRE_BLOOD_PRESSURE2 = (int) $data['PRE_BLOOD_PRESSURE2'];
            $PRE_HEART_RATE      = (int) $data['PRE_HEART_RATE'];
            $PRE_O2_SATURATION   = (int) $data['PRE_O2_SATURATION'];

            $POST_WEIGHT          = (int) $data['POST_WEIGHT'];
            $POST_BLOOD_PRESSURE  = (int) $data['POST_BLOOD_PRESSURE'];
            $POST_BLOOD_PRESSURE2 = (int) $data['POST_BLOOD_PRESSURE2'];
            $POST_HEART_RATE      = (int) $data['POST_HEART_RATE'];
            $POST_O2_SATURATION   = (int) $data['POST_O2_SATURATION'];
            $IS_INCOMPLETE        = (bool) $data['IS_INCOMPLETE'];

            DB::beginTransaction();

            if ($ID > 0) {
                if ($IS_INCOMPLETE == true) {
                    // Do nothing
                } elseif ($IS_PF == true) {
                    // Do nothing
                } else {
                    if ($PRE_WEIGHT == 0 || $PRE_BLOOD_PRESSURE == 0 || $PRE_BLOOD_PRESSURE2 == 0 || $PRE_HEART_RATE == 0 || $PRE_O2_SATURATION == 0) {
                        $this->hemoServices->StatusUpdate($ID, 3);                                  // VOID
                        $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 2); // ABSENT
                        DB::commit();
                        return;
                    }
                }

                $this->scheduleServices->StatusUpdate($CONTACT_ID, $DATE, $LOCATION_ID, 1); //PRESENT
                if ($POST_WEIGHT == 0 || $POST_BLOOD_PRESSURE == 0 || $POST_BLOOD_PRESSURE2 == 0 || $POST_HEART_RATE == 0 || $POST_O2_SATURATION == 0 || empty($TIME_START) == true || empty($TIME_END) == true) {
                    if ($IS_INCOMPLETE == true || $IS_PF == true) {
                        $this->hemoServices->StatusUpdate($ID, 2);   // POSTED
                        $this->hemoServices->getMakeJournal($ID);    // to journal
                        $this->hemoServices->makeItemInventory($ID); // item inventory
                    } else {
                        $this->hemoServices->StatusUpdate($ID, 4); // UNPOSTED
                    }
                } else {
                    $this->hemoServices->StatusUpdate($ID, 2);   // POSTED
                    $this->hemoServices->getMakeJournal($ID);    // to journal
                    $this->hemoServices->makeItemInventory($ID); // item inventory
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
    public function userDefaultUserDate()
    {
        $this->userServices->resetDefaultTime();
    }
}
