<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PatientStatusServices
{
    private $itemServices;
    public function __construct(ItemServices $itemServices)
    {
        $this->itemServices = $itemServices;
    }
    public function getList(int $month, int $year)
    {


        return DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_ADMISSION) = '$month' and year(contact.DATE_ADMISSION) = '$year' and contact.LOCATION_ID = location.ID ) as `NEW` "),
                DB::raw("(select count(*)  from contact inner join patient_confinement on patient_confinement.patient_id = contact.ID where contact.TYPE=3 and month(patient_confinement.DATE_START) = '$month' and year(patient_confinement.DATE_START) = '$year' and contact.LOCATION_ID = location.ID ) as `CONFINEMENT` "),
                DB::raw("(select count(*)  from contact inner join patient_transfer on patient_transfer.patient_id = contact.ID where contact.TYPE=3 and month(patient_transfer.DATE_TRANSFER) = '$month' and year(patient_transfer.DATE_TRANSFER) = '$year' and contact.LOCATION_ID = location.ID ) as `TRANSFER` "),
                DB::raw("(select count(*)  from contact where contact.TYPE=3 and month(contact.DATE_EXPIRED) = '$month' and year(contact.DATE_EXPIRED) = '$year' and contact.LOCATION_ID = location.ID ) as `EXPIRED` ")


            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }

    public function getTreatmentSummaryList(int $month, int $year, int $prev_month, int $prev_year)
    {
        $PHIC_ITEM_ID = $this->itemServices->PHIC_ITEM_ID;
        $PRIMING_ITEM_ID = $this->itemServices->PRIMING_ITEM_ID;

        return DB::table('location')
            ->select([
                'ID',
                'NAME',
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PHIC_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0'  ) as TOTAL_PHILHEALTH"),
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PRIMING_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0'  ) as TOTAL_PRIMING"),
                DB::raw("( select count(service_charges.ID) from service_charges  where not exists(	select service_charges_items.`ID` from service_charges_items where (service_charges_items.ITEM_ID = '$PHIC_ITEM_ID'  OR  service_charges_items.`ITEM_ID` = '$PRIMING_ITEM_ID') and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$month' and year(service_charges.DATE) = '$year' and service_charges.WALK_IN = '0' ) as TOTAL_REGULAR"),

                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PHIC_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0'  ) as PREV_TOTAL_PHILHEALTH"),
                DB::raw("( select count(service_charges.ID) from service_charges  where exists(	select service_charges_items.`ID` from service_charges_items where service_charges_items.ITEM_ID = '$PRIMING_ITEM_ID' and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0'  ) as PREV_TOTAL_PRIMING"),
                DB::raw("( select count(service_charges.ID) from service_charges  where not exists(	select service_charges_items.`ID` from service_charges_items where (service_charges_items.ITEM_ID = '$PHIC_ITEM_ID'  OR  service_charges_items.`ITEM_ID` = '$PRIMING_ITEM_ID') and service_charges_items.`SERVICE_CHARGES_ID` = service_charges.`ID`) and service_charges.LOCATION_ID = location.ID and month(service_charges.DATE) = '$prev_month' and year(service_charges.DATE) = '$prev_year' and service_charges.WALK_IN = '0' ) as PREV_TOTAL_REGULAR")
            ])
            ->where('INACTIVE', '0')
            ->where('USED_DRY_WEIGHT', '=', true)
            ->get();
    }
}