<?php

namespace App\Exports;

use App\Services\PatientReportServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatientSalesReportExport implements FromCollection, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $patientReportServices;
    protected string $scFrom;
    protected string $scTo;
    protected string $pFrom;
    protected string $pTo;
    protected int $locationId;
    protected int $patientId;
    public function __construct(PatientReportServices $patientReportServices, string $scFrom, string $scTo, string $pFrom, string $pTo, int $locationId, int $patientId)
    {
        $this->patientReportServices = $patientReportServices;
        $this->scFrom = $scFrom;
        $this->scTo = $scTo;
        $this->pFrom    = $pFrom;
        $this->pTo = $pTo;
        $this->locationId = $locationId;
        $this->patientId = $patientId;
    }

    public function collection()
    {
        $data =  $this->patientReportServices->generateSalesReportData($this->pFrom, $this->pTo, $this->scFrom, $this->scTo, $this->locationId, $this->patientId)->toArray();

        $headers = [
            'PN'        => 'PATIENT NAME',
            'IN'        => 'ITEM NAME',
            'SC_DATE'   => '(SC)DATE',
            'SC_CODE'   => '(SC)CODE',
            'SC_AMOUNT' => '(SC)AMOUNT',
            'P_DATE'    => '(P)Date',
            'P_CODE'    => '(P)Code',
            'P_METHOD'  => '(P)METHOD',
            'P_DEPOSIT' => '(P)DEPOSIT',
            'P_PAID'    => '(P)PAID',
            'BAL'       => 'BALANCE',
            'DOCTOR'    => 'DOCTOR',
            'LOCATION'  => 'LOCATION',
        ];


        $tempName  = '';
        $PREV_SC_ITEM_REF_ID = 0;
        $TOTAL_CHARGE = 0;
        $TOTAL_PAID = 0;
        $running_balance = 0;
        $NO_OF_PATIENT = 0;
        $sc_code = '';
        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row

        // Loop through your data and format it for export
        foreach ($data as $list) {


            if ($sc_code == $list->SC_CODE) {
                $is_sc = false;
            } else {
                $is_sc = true;
            }

            if ($PREV_SC_ITEM_REF_ID == $list->SC_ITEM_REF_ID) {
                $not_to_charge = true;
            } else {
                $not_to_charge = false;
            }

            if ($tempName == $list->PATIENT_NAME) {
                $is_add = false;
                if ($not_to_charge == false) {
                    $running_balance = $running_balance + $list->SC_AMOUNT ?? 0;
                }
            } else {
                $is_add = true;
                $is_sc = true;
                $running_balance = $list->SC_AMOUNT ?? 0;
                $NO_OF_PATIENT = $NO_OF_PATIENT + 1;
            }

            $running_balance = $running_balance - $list->PP_PAID;

            $tempName = $list->PATIENT_NAME;
            $sc_code = $list->SC_CODE;
            $PREV_SC_ITEM_REF_ID = $list->SC_ITEM_REF_ID ?? 0;

            if ($not_to_charge == false) {
                $TOTAL_CHARGE = $TOTAL_CHARGE + $list->SC_AMOUNT;
            }

            if ($list->PP_PAID > 0) {
                $TOTAL_PAID = $TOTAL_PAID + $list->PP_PAID ?? 0;
            }

            if ($is_add) {
                $rowData = ['PN'        => '', 'IN'        => '', 'SC_DATE'   => '', 'SC_CODE'   => '', 'SC_AMOUNT' => '', 'P_DATE'    => '', 'P_CODE'    => '', 'P_METHOD'  => '', 'P_DEPOSIT' => '', 'P_PAID'    => '', 'BAL'       => '', 'DOCTOR'    => '', 'LOCATION'  => '',];

                $finalData[] = array_values($rowData);
            }

            $rowData = [
                'PN'        => $is_add ? $list->PATIENT_NAME : '',
                'IN'        => $list->ITEM_NAME ?? '',
                'SC_DATE'   => $is_sc ? $list->SC_DATE : '',
                'SC_CODE'   => $is_sc ? $list->SC_CODE : '',
                'SC_AMOUNT' => $not_to_charge ? 0 : $list->SC_AMOUNT,
                'P_DATE'    => $list->PP_DATE ?? '',
                'P_CODE'    => $list->PP_CODE ?? '',
                'P_METHOD'  => $list->PAYMENT_METHOD ?? '',
                'P_DEPOSIT' => $list->PP_DEPOSIT ?? '',
                'P_PAID'    => $list->PP_PAID ?? '',
                'BAL'       => $running_balance,
                'DOCTOR'    =>  $is_add ? $list->DOCTOR_NAME : '',
                'LOCATION'  => $is_add ? $list->LOCATION_NAME : '',
            ];

            $finalData[] = array_values($rowData);
        }
        // BLANK
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        // No. of Patient - SC TOTAL
        $rowData = ['PN' => 'No. of Patient: ' . $NO_OF_PATIENT, 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => 'TOTAL CHARGE :' . $TOTAL_CHARGE, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // PAID TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => 'TOTAL PAID :' . $TOTAL_PAID, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // BALANCE TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => '', 'BAL' => '', 'DOCTOR' => 'TOTAL BALANCE :' . $TOTAL_CHARGE - $TOTAL_PAID, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}
