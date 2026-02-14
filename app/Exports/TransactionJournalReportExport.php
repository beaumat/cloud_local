<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionJournalReportExport implements FromCollection, ShouldAutoSize
{
    protected  $dataList = [];

    public function __construct($dataList)
    {
        $this->dataList = $dataList;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $TOTAL_DEBIT = 0;
        $TOTAL_CREDIT = 0;
        $finalData = [];

        $headers = [
            'JOURNAL_NO'    => 'Jrnl#',
            'DATE'          => 'Date',
            'TYPE'          => 'Type',
            'TX_CODE'       => 'Code',
            'TX_NAME'       => 'Name',
            'LOCATION'      => 'Location',
            'ACCOUNT_TITLE' => 'Account Title',
            'TX_NOTES'      => 'Particulars',
            'DEBIT'         => 'Debit',
            'CREDIT'        => 'Credit',
        ];
        $finalData[] = array_values($headers);

        foreach ($this->dataList as $list) {
            $rowData = [
                'JOURNAL_NO'    => $list->JOURNAL_NO,
                'DATE'          => date('m/d/Y', strtotime($list->DATE)),
                'TYPE'          => $list->TYPE,
                'TX_CODE'       => $list->TX_CODE,
                'TX_NAME'       => $list->TX_NAME,
                'LOCATION'      => $list->LOCATION,
                'ACCOUNT_TITLE' => $list->ACCOUNT_TITLE,
                'TX_NOTES'      => $list->TX_NOTES,
                'DEBIT'         => $list->DEBIT > 0 ? $list->DEBIT : '',
                'CREDIT'        => $list->CREDIT > 0 ? $list->CREDIT : '',

            ];
            if ($list->DEBIT > 0) {
                $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT ?? 0;
            }

            if ($list->CREDIT > 0) {
                $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT ?? 0;
            }


            $finalData[] = array_values($rowData);
        }

        $rowData = [
            'JOURNAL_NO'    => '',
            'DATE'          => '',
            'TYPE'          => '',
            'TX_CODE'       => '',
            'TX_NAME'       => '',
            'LOCATION'      => '',
            'ACCOUNT_TITLE' => '',
            'TX_NOTES'      => '',
            'DEBIT'         => $TOTAL_DEBIT,
            'CREDIT'        => $TOTAL_CREDIT,

        ];

        $finalData[] = array_values($rowData);




        return collect($finalData);
    }
}

