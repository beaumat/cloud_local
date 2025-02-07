<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\OtherServices;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PhilhealthMonitoringExport implements FromCollection, ShouldAutoSize
{

    protected $dataList = [];

    public function __construct($dataList)
    {
        $this->dataList = $dataList;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $finalData = [];


        $headers = [
            'NO'            => 'No.',
            'DATE'          => 'Date Trans.',
            'LHIO'          => 'Series LHIO',
            'NAME'          => 'Name of Patient',
            'CON'           => 'Confinement Period',
            'NOT'           => 'No. of Treatment',
            'FIRST_CASE'    => 'First Case Amount',
            'D_PAID'        => 'Date Paid',
            'OR'            => 'OR Number',
            'WTAX'          => 'Wtax',
            'PAID'          => 'Paid',
            'GROSS'         => 'Gross',
            'PF'            => 'Doctor PF',
            'NET'           => 'Net Amount',
            'STATUS'        => 'Status',
        ];


        $finalData[] = array_values($headers);
        $running = 0;
        $TOTAL_AMOUNT = 0;
        $TOTAL_WTAX = 0;
        $TOTAL_PAID = 0;
        $TOTAL_GROSS = 0;
        $TOTAL_PF = 0;
        $TOTAL_NET = 0;

        foreach ($this->dataList as $list) {
            $running++;
            $TOTAL_AMOUNT += $list->P1_TOTAL;
            $TOTAL_WTAX += $list->TAX_AMOUNT;
            $TOTAL_PAID += $list->PAID_AMOUNT;
            $TOTAL_GROSS += $list->PAID_AMOUNT + $list->TAX_AMOUNT;
            $TOTAL_PF += $list->DOCTOR_PF;
            $TOTAL_NET += $list->PAID_AMOUNT + $list->TAX_AMOUNT - $list->DOCTOR_PF;
            $rowData = [
                'NO'            => $running,
                'DATE'          => date('M/d/Y', strtotime($list->AR_DATE)),
                'LHIO'          => $list->AR_NO,
                'NAME'          => $list->CONTACT_NAME,
                'CON'           => OtherServices::formatDates($list->CONFINE_PERIOD),
                'NOT'           => $list->HEMO_TOTAL,
                'FIRST_CASE'    => number_format($list->P1_TOTAL, 2),
                'D_PAID'        => $list->PAID_DATE ? date('M/d/Y', strtotime($list->PAID_DATE)) : '',
                'OR'            => $list->OR_NUMBER ?? '',
                'WTAX'          => $list->TAX_AMOUNT > 0 ? number_format($list->TAX_AMOUNT, 2) : '',
                'PAID'          => $list->PAID_AMOUNT > 0 ? number_format($list->PAID_AMOUNT, 2) : '',
                'GROSS'         => $list->PAID_AMOUNT > 0 ?  number_format($list->PAID_AMOUNT + $list->TAX_AMOUNT, 2) : '',
                'PF'            => $list->DOCTOR_PF > 0 ? number_format($list->DOCTOR_PF, 2) : '',
                'NET'           => $list->DOCTOR_PF > 0 ?  number_format($list->PAID_AMOUNT + $list->TAX_AMOUNT - $list->DOCTOR_PF, 2) : ' ',
                'STATUS'        => $list->DOCTOR_PF > 0 ? ($list->DOCTOR_PF_BALANCE > 0 ? 'Not Paid' : 'Paid') : ' '
            ];

            $finalData[] = array_values($rowData);
        }



        $rowData = [
            'NO'            => '',
            'DATE'          => '',
            'LHIO'          => '',
            'NAME'          => '',
            'CON'           => '',
            'NOT'           => '',
            'FIRST_CASE'    => '',
            'D_PAID'        => '',
            'OR'            => '',
            'WTAX'          => '',
            'PAID'          => '',
            'GROSS'         => '',
            'PF'            => '',
            'NET'           => '',
            'STATUS'        => '',
        ];
        $finalData[] = array_values($rowData);

        $rowData = [
            'NO'            => '',
            'DATE'          => '',
            'LHIO'          => '',
            'NAME'          => '',
            'CON'           => '',
            'NOT'           => '',
            'FIRST_CASE'    => number_format($TOTAL_AMOUNT, 2),
            'D_PAID'        => '',
            'OR'            => '',
            'WTAX'          => number_format($TOTAL_WTAX, 2),
            'PAID'          => number_format($TOTAL_PAID, 2),
            'GROSS'         => number_format($TOTAL_GROSS, 2),
            'PF'            => number_format($TOTAL_PF, 2),
            'NET'           => number_format($TOTAL_NET, 2),
            'STATUS'        => '',
        ];

        $finalData[] = array_values($rowData);

        return collect($finalData);
    }
}
