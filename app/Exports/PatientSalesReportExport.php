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
    protected array $patientData;
    protected array $itemData;
    public function __construct(PatientReportServices $patientReportServices, string $scFrom, string $scTo, string $pFrom, string $pTo, int $locationId, array $patientData, array $itemData)
    {
        $this->patientReportServices = $patientReportServices;
        $this->scFrom = $scFrom;
        $this->scTo = $scTo;
        $this->pFrom    = $pFrom;
        $this->pTo = $pTo;
        $this->locationId = $locationId;
        $this->patientData = $patientData;
        $this->itemData = $itemData;
    }

    public function collection()
    {
        $data =  $this->patientReportServices->generateSalesReportData($this->pFrom, $this->pTo, $this->scFrom, $this->scTo, $this->locationId, $this->patientData, $this->itemData)->toArray();

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



        $CASH_AMOUNT = 0;
        $PHILHEALTH_AMOUNT = 0;
        $DSWD_AMOUNT = 0;
        $LINGAP_AMOUNT = 0;
        $PCSO_AMOUNT = 0;
        $OTHER_GL_AMOUNT = 0;


        $running_balance = 0;
        $NO_OF_PATIENT = 0;
        $NO_OF_TREATMENT = 0;
        $sc_code = '';
        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row

        // Loop through your data and format it for export
        foreach ($data as $list) {

            if ($sc_code == $list->SC_CODE) {
                $is_sc = false;
            } else {
                $NO_OF_TREATMENT = $NO_OF_TREATMENT  + 1;
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


                if ($list->PAYMENT_METHOD_ID == 1) {
                    //Cash
                    $CASH_AMOUNT = $CASH_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 91) {
                    //Philhealth
                    $PHILHEALTH_AMOUNT = $PHILHEALTH_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 92) {
                    //DSWD
                    $DSWD_AMOUNT = $DSWD_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 93) {
                    //LINGAP
                    $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->PP_PAID ?? 0;
                }

                if ($list->PAYMENT_METHOD_ID == 94) {
                    //PCSO
                    $PCSO_AMOUNT = $PCSO_AMOUNT + $list->PP_PAID ?? 0;
                }
                if ($list->PAYMENT_METHOD_ID == 96) {
                    //Other GL
                    $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->PP_PAID ?? 0;
                }
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
        $rowData = ['PN' => 'No. of Patient: ' . $NO_OF_PATIENT, 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => 'No. of Treatment: ' . $NO_OF_TREATMENT, 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'Cash Paid: ' . $CASH_AMOUNT, 'BAL' => '', 'DOCTOR' => 'TOTAL CHARGE :' . $TOTAL_CHARGE, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // PAID TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'Philhealth Paid: ' . $PHILHEALTH_AMOUNT, 'BAL' => '', 'DOCTOR' => 'TOTAL PAID :' . $TOTAL_PAID, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);
        // BALANCE TOTAL
        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'DSWD Paid: ' . $DSWD_AMOUNT, 'BAL' => '', 'DOCTOR' => 'TOTAL BALANCE :' . $TOTAL_CHARGE - $TOTAL_PAID, 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'LINGAP Paid: ' . $LINGAP_AMOUNT, 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'PCSO Paid: ' . $PCSO_AMOUNT, 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);

        $rowData = ['PN' => '', 'IN' => '', 'SC_DATE' => '', 'SC_CODE' => '', 'SC_AMOUNT' => '', 'P_DATE' => '', 'P_CODE' => '', 'P_METHOD' => '', 'P_DEPOSIT' => '', 'P_PAID' => 'Other GL Paid: ' . $OTHER_GL_AMOUNT, 'BAL' => '', 'DOCTOR' => '', 'LOCATION' => '',];
        $finalData[] = array_values($rowData);



        return collect($finalData);
    }
}
