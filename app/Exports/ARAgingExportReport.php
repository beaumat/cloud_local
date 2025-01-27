<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ARAgingExportReport implements FromCollection
{

    protected  $dataList = [];
    protected  bool $isSummary;
    public function __construct($dataList, bool $isSummary)
    {
        $this->dataList = $dataList;
        $this->isSummary = $isSummary;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $finalData = [];
        $headers = [];

        if ($this->isSummary) {
            $headers = [
                'CUSTOMER'  => 'CUSTOMER',
                'CURRENT'   => 'CURRENT',
                '1-30'      => '1-30',
                '31-60'     => '31-60',
                '61-90'     => '61-90',
                '90-OVER'   => '90-OVER',
                'BALANCE'   => 'BALANCE'
            ];
            $finalData[] = array_values($headers);
        } else {
            $headers = [
                'DATE'              => 'DATE',
                'REFERENCE'         => 'REFERENCE',
                'CUSTOMER'          => 'CUSTOMER',
                'TERMS'             => 'TERMS',
                'DUE-DATE'          => 'DUE-DATE',
                'AGING'             => 'AGING',
                'OPEN-BALANCE'      => 'OPEN-BALANCE',
                'LOCATION'          => 'LOCATION'
            ];
            $finalData[] = array_values($headers);
        }

        if ($this->isSummary) {

            $DUE_CURRENT = 0;
            $DUE_1_30 = 0;
            $DUE_31_60 = 0;
            $DUE_61_90 = 0;
            $DUE_90_OVER = 0;
            $BALANCE = 0;


            foreach ($this->dataList as $list) {
                $rowData = [
                    'ACCOUNT'  => $list['ACCOUNT'],
                    'AMOUNT'   => $list['AMOUNT'],
                ];




                foreach ($this->dataList as $list) {

                    $rowData = [
                        'CUSTOMER'  => $list->CONTACT_NAME,
                        'CURRENT'   => number_format($list->DUE_CURRENT, 2),
                        '1-30'     => number_format($list->DUE_1_30, 2),
                        '31-60'     => number_format($list->DUE_31_60, 2),
                        '61-90'     => number_format($list->DUE_61_90, 2),
                        '90-OVER'   => number_format($list->DUE_90_OVER, 2),
                        'BALANCE'   => number_format($list->BALANCE, 2)
                    ];
                    $finalData[] = array_values($rowData);
                    $DUE_CURRENT = $DUE_CURRENT + $list->DUE_CURRENT;
                    $DUE_1_30 = $DUE_1_30 + $list->DUE_1_30;
                    $DUE_31_60 = $DUE_31_60 + $list->DUE_31_60;
                    $DUE_61_90 = $DUE_61_90 + $list->DUE_61_90;
                    $DUE_90_OVER = $DUE_90_OVER + $list->DUE_90_OVER;
                    $BALANCE = $BALANCE + $list->BALANCE;
                }

                $rowData = [
                    'CUSTOMER'  => '',
                    'CURRENT'   => '',
                    '1-30'      => '',
                    '31-60'     =>  '',
                    '61-90'     => '',
                    '90-OVER'   => '',
                    'BALANCE'   => ''
                ];

                $finalData[] = array_values($rowData);

                $rowData = [
                    'CUSTOMER'  => number_format($DUE_CURRENT, 2),
                    'CURRENT'   => number_format($DUE_1_30, 2),
                    '1-30'      => number_format($DUE_31_60, 2),
                    '31-60'     => number_format($DUE_61_90, 2),
                    '61-90'     => number_format($DUE_61_90, 2),
                    '90-OVER'   => number_format($DUE_90_OVER, 2),
                    'BALANCE'   => number_format($BALANCE, 2)
                ];

                $finalData[] = array_values($rowData);
            }
        } else {

            $TMP_AGING = '';
            $COMPARE = '';
            $RUN_BALANCE = 0;
            $RUN_TOTAL = 0;


        



                foreach ($this->dataList as $list) {
                if ($list->AGING <= 0){
                    if ($D_CURRENT == false)
                        if ($COMPARE != $TMP_AGING && $RUN_BALANCE > 0)

                        $rowData = [
                            'DATE'              => TOTAL . ' '.  $TMP_AGING ,
                            'REFERENCE'         => '',
                            'CUSTOMER'          => '',
                            'TERMS'             => '',
                            'DUE-DATE'          => '',
                      'AGING'            => '',
                            'OPEN-BALANCE'      =>  number_format($RUN_BALANCE, 2),
                            'LOCATION'          => ''
                        ];
                        $finalData[] = array_values($rowData);
                  

                 


                        $rowData = [
                            'DATE'              => 'CURRENT' ,
                            'REFERENCE'         => '',
                            'CUSTOMER'          => '',
                            'TERMS'             => '',
                            'DUE-DATE'          => '',
                            'AGING'             => '',
                            'OPEN-BALANCE'      => '',
                            'LOCATION'          => ''
                        ];
                        $finalData[] = array_values($rowData);

                  
                            $D_CURRENT = true;
                            $TMP_AGING = 'CURRENT';
                            $RUN_BALANCE = 0;
                        
                
                    }else if ($list->AGING <= 30)
                    {
                        if ($D_1_30 == false) {
                        if ($RUN_BALANCE > 0) {

                            $rowData = [
                                'DATE'              => TOTAL . ' '. $TMP_AGING ,
                                'REFERENCE'         => '',
                                'CUSTOMER'          => '',
                                'TERMS'             => '',
                                'DUE-DATE'          => '',
                                'AGING'             => '',
                                'OPEN-BALANCE'      => number_format($RUN_BALANCE, 2),
                                'LOCATION'          => ''
                            ];
                            $finalData[] = array_values($rowData);
                    
                        }
                           
                        $rowData = [
                            'DATE'              => '1-30' ,
                            'REFERENCE'         => '',
                            'CUSTOMER'          => '',
                            'TERMS'             => '',
                            'DUE-DATE'          => '',
                            'AGING'             => '',
                            'OPEN-BALANCE'      => '',
                            'LOCATION'          => ''
                        ];
                        $finalData[] = array_values($rowData);

            
                            $D_1_30 = true;
                            $TMP_AGING = '1-30';
                            $RUN_BALANCE = 0;
                        }
                    }
                   
                @elseif ($list->AGING <= 60)
                    @if ($D_31_60 == false)
                        @if ($RUN_BALANCE > 0)
                            <tr>
                                <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right text-primary">
                                    {{ number_format($RUN_BALANCE, 2) }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="h4 text-primary">&nbsp;</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="h4 text-primary">31-60</td>
                        </tr>
                        @php
                            $D_31_60 = true;
                            $TMP_AGING = '31-60';
                            $RUN_BALANCE = 0;
                        @endphp
                    @endif
                @elseif ($list->AGING <= 90)
                    @if ($D_61_90 == false)
                        @if ($RUN_BALANCE > 0)
                            <tr>
                                <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right text-primary">
                                    {{ number_format($RUN_BALANCE, 2) }} </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="h4 text-primary">&nbsp;</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="h4 text-primary">61-90</td>
                        </tr>
                        @php
                            $D_61_90 = true;
                            $TMP_AGING = '61-90';
                            $RUN_BALANCE = 0;
                        @endphp
                    @endif
                @else
                    @if ($D_91_OVER == false)
                        @if ($RUN_BALANCE > 0)
                            <tr>
                                <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right text-primary">
                                    {{ number_format($RUN_BALANCE, 2) }}
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="h4 text-primary">&nbsp;</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="h4 text-primary">91 OVER</td>
                        </tr>
                        @php
                            $D_91_OVER = true;
                            $TMP_AGING = '91 OVER';
                            $RUN_BALANCE = 0;
                        @endphp
                    @endif
                @endif





                @php
                    $RUN_BALANCE = $RUN_BALANCE + $list->BALANCE_DUE;
                @endphp




                <tr>
                    <td>{{ date('M/d/Y', strtotime($list->DATE)) }}</td>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->CONTACT_NAME }}</td>
                    <td>{{ $list->PAYMENT_TERMS }}</td>
                    <td>{{ date('M/d/Y', strtotime($list->DUE_DATE)) }}</td>
                    <td>{{ $list->AGING < 1 ? '' : $list->AGING }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td>{{ $list->LOCATION_NAME }}</td>
                </tr>

                @php
                    $COMPARE = $TMP_AGING;
                    $RUN_TOTAL = $RUN_TOTAL + $list->BALANCE_DUE ?? 0;
                @endphp
            @endforeach


            <tr>
                <td class="text-primary">TOTAL {{ $TMP_AGING }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right text-primary">
                    {{ number_format($RUN_BALANCE, 2) }}
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right text-danger">{{ number_format($RUN_TOTAL, 2) }}</td>
                <td></td>


            </tr>
            }





        }
        return collect($finalData);
    }
}
