<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\Items;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PatientReportServices
{
    private $itemServices;
    public function __construct(ItemServices $itemServices)
    {
        $this->itemServices = $itemServices;
    }

    /**
     * @param array $patientData
     */

    public function generateSalesReportData(string $ppFrom, string $ppTo, string $scFrom, string $scTo, int  $locatoinId, array  $patientData = [], array $itemData = []): Collection
    {
        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID'
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where(function ($query) use (&$ppFrom, &$ppTo, &$scFrom, &$scTo) {
                $query->whereBetween('pp.DATE', [$ppFrom, $ppTo])
                    ->orWhereBetween('sc.DATE', [$scFrom, $scTo]);
            })
            ->when($locatoinId > 0, function ($query) use (&$locatoinId) {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData) {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData) {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function generatePrevCollection(string $scFrom, string $scTo, int  $locatoinId, array  $patientData = [], array $itemData = []): Collection
    {

        $results = DB::table('service_charges_items as sci')
            ->select([
                'sc.ID as SC_ID',
                'sc.CODE as SC_CODE',
                'sc.DATE as SC_DATE',
                'sci.ID as SC_ITEM_REF_ID',
                'sci.AMOUNT as SC_AMOUNT',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as PATIENT_NAME"),
                'i.CODE as ITEM_CODE',
                'i.DESCRIPTION as ITEM_NAME',
                'pp.ID as PP_ID',
                DB::raw('IFNULL(pp.RECEIPT_DATE,pp.DATE)  as PP_DATE'),
                DB::raw('IFNULL(pp.RECEIPT_REF_NO,pp.CODE) as PP_CODE'),
                'pm.DESCRIPTION as PAYMENT_METHOD',
                DB::raw('IFNULL(pp.AMOUNT, 0) as PP_DEPOSIT'),
                DB::raw('IFNULL(ppc.AMOUNT_APPLIED, 0) as PP_PAID'),
                'l.NAME as LOCATION_NAME',
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME '),
                'pp.PAYMENT_METHOD_ID'
            ])
            ->join('item as i', 'i.ID', '=', 'sci.ITEM_ID')
            ->join('service_charges as sc', 'sc.ID', '=', 'sci.SERVICE_CHARGES_ID')
            ->join('location as l', 'l.ID', '=', 'sc.LOCATION_ID')
            ->join('contact as c', 'c.ID', '=', 'sc.PATIENT_ID')
            ->leftJoin('patient_payment_charges as ppc', 'ppc.SERVICE_CHARGES_ITEM_ID', '=', 'sci.ID')
            ->leftJoin('patient_payment as pp', function ($join) {
                $join->on('pp.ID', '=', 'ppc.PATIENT_PAYMENT_ID')
                    ->on('pp.LOCATION_ID', '=', 'sc.LOCATION_ID');
            })
            ->leftJoin('payment_method as pm', 'pm.ID', '=', 'pp.PAYMENT_METHOD_ID')
            ->where('pp.PAYMENT_METHOD_ID', 1)
            ->where(function ($query) use (&$scFrom, &$scTo) {
                $query->whereBetween('pp.DATE', [$scFrom, $scTo])
                    ->whereNotBetween('sc.DATE', [$scFrom, $scTo]);
            })
            ->when($locatoinId > 0, function ($query) use (&$locatoinId): void {
                $query->where('sc.LOCATION_ID', $locatoinId);
            })
            ->when($patientData, function ($query) use (&$patientData): void {
                $array = $patientData;
                $query->whereIn('sc.PATIENT_ID', $array);
            })
            ->when($itemData, function ($query) use (&$itemData): void {
                $array = $itemData;
                $query->whereIn('sci.ITEM_ID', $array);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
    public function getItemListViaReport(int $LOCATION_ID, string $DATE_FROM, string $DATE_TO): array|Collection
    {

        $result = Items::query()
            ->select(['ID', 'DESCRIPTION'])
            ->whereExists(function ($query) use (&$LOCATION_ID, &$DATE_FROM, &$DATE_TO) {
                $query->select(DB::raw(1))
                    ->from('service_charges as s')
                    ->join('service_charges_items as ci', function ($join) {
                        $join->on('ci.SERVICE_CHARGES_ID', '=', 's.ID')
                            ->on('ci.ITEM_ID', '=', 'item.ID');
                    })
                    ->where('s.LOCATION_ID', '=', $LOCATION_ID)
                    ->whereBetween('s.DATE', [$DATE_FROM, $DATE_TO]);
            })
            ->orderBy('DESCRIPTION', 'asc')
            ->get();

        return $result;
    }

    public function getMonthlyTreatment(int $year, int $month, array  $dayList = [], array $patient = [], int $LocationId)
    {
        $PHIC_ITEM_ID = $this->itemServices->PHIC_ITEM_ID;
        $PRIMING_ITEM_ID = $this->itemServices->PRIMING_ITEM_ID;

        foreach ($dayList as $day) {
            $coldate = date('d', strtotime($day));
            $sql = "(select if( i.ITEM_ID  = $PHIC_ITEM_ID , 1, if(i.ITEM_ID = $PRIMING_ITEM_ID , 2 ,0) ) from hemodialysis as d  join service_charges as s on (s.DATE = d.DATE and s.LOCATION_ID = d.LOCATION_ID and s.PATIENT_ID = d.CUSTOMER_ID)  inner join service_charges_items as i on i.SERVICE_CHARGES_ID = s.ID where d.LOCATION_ID ='$LocationId' and d.DATE = '$day' and d.CUSTOMER_ID = contact.ID and d.STATUS_ID = 2  and i.ITEM_ID in ($PHIC_ITEM_ID,$PRIMING_ITEM_ID)  order by i.ITEM_ID asc limit 1 ) as '$coldate'";
            $selectArrayTR[] = DB::raw($sql);
        }

        $results = Contacts::query()
            ->select($selectArrayTR)
            ->addSelect([
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT_NAME")
            ])
            ->whereExists(function ($query) use (&$year, &$month, &$LocationId) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis as h')
                    ->whereColumn('h.CUSTOMER_ID', '=', 'contact.ID')
                    ->where('h.STATUS_ID', '=', 2)
                    ->where('h.LOCATION_ID', '=', $LocationId)
                    ->whereMonth('h.DATE', '=', $month)
                    ->whereYear('h.DATE', '=', $year);
            })
            ->orderBy('contact.LAST_NAME', 'asc')
            ->get();


        return  $results;
    }
}
