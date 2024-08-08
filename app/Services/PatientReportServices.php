<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class PatientReportServices
{
    public function generateSalesReportData(string $ppFrom, string $ppTo, string $scFrom, string $scTo, int  $locatoinId, int $patientId = 0)
    {
        $results = DB::table('service_charges_items as sci')
            ->select(
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
                DB::raw('(select d.PRINT_NAME_AS  from patient_doctor  as pd join contact as d on d.ID = pd.DOCTOR_ID where pd.PATIENT_ID = c.ID limit 1) as DOCTOR_NAME ')
            )
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
            ->when($patientId > 0, function ($query) use (&$patientId) {
                $query->where('sc.PATIENT_ID', $patientId);
            })
            ->orderBy('c.LAST_NAME')
            ->orderBy('sc.CODE')
            ->orderBy('sci.ID')
            ->get();

        return $results;
    }
}
