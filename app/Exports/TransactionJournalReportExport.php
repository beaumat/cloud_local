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


// <tr>
// <td>Jrnl#</td>
// <td>Date</td>
// <td class="col-1">Type</td>
// <td class="col-1">Ref #</td>
// <td class="col-2">Name</td>
// <td class="col-1">Location</td>
// <td class="col-2">Account Title</td>
// <td class="col-3">Particular</td>
// <th class="col-1 text-right">Debit</th>
// <th class="col-1 text-right">Credit</th>
// </tr>
// @foreach ($dataList as $list)
// <tr>
//     <td>{{ $list->JOURNAL_NO }}</td>
//     <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
//     <td>{{ $list->TYPE }}</td>
//     <td>{{ $list->TX_CODE }}</td>
//     <td>{{ $list->TX_NAME }}</td>
//     <td>{{ $list->LOCATION }}</td>
//     <td>{{ $list->ACCOUNT_TITLE }}</td>
//     <td>{{ $list->TX_NOTES }}</td>
//     <td class="text-right">
//         {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
//         @php
//             if ($list->DEBIT > 0) {
//                 $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT ?? 0;
//             }
//         @endphp
//     </td>
//     <td class="text-right">
//         {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
//         @php
//             if ($list->CREDIT > 0) {
//                 $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT ?? 0;
//             }
//         @endphp
//     </td>
// </tr>
// @endforeach
// <tr>
// <td></td>
// <td></td>
// <td></td>
// <td></td>
// <td></td>
// <td></td>
// <td></td>
// <td></td>
// <td class="text-right font-weight-bold">
//     @if ($TOTAL_DEBIT)
//         {{ number_format($TOTAL_DEBIT, 2) }}
//     @else
//         0.00
//     @endif
// </td>
// <td class="text-right font-weight-bold">
//     @if ($TOTAL_CREDIT)
//         {{ number_format($TOTAL_CREDIT, 2) }}
//     @else
//         0.00
//     @endif
// </td>
// </tr>
