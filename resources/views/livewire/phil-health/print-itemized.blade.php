<div class="mt-1 row">
    <div class="col-9">
        <table class="w-100" border="2">
            <thead>
                <tr class="text-center text-md">
                    <th>SERVICE DATE</th>
                    <th>ITEM NAME</th>
                    <th>UNIT OF MEASUREMENT</th>
                    <th>PRICE</th>
                    <th>QTY</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            @php
                $TOTAL = 0;
                $TYPE = '';
                $posted = false;
            @endphp
            @php
                $row = 0;
            @endphp
            <tbody class=''>
                @foreach ($dataList as $list)
                    @if ($TYPE == '')
                    @elseif ($TYPE != $list->TYPE_NAME)
                        <tr class="font-weight-bold">
                            <td class="text-center text-xs">
                                @if (isset($breakDownDate[$row]))
                                    {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                                @endif

                                @php
                                    $row++;
                                @endphp
                            </td>
                            <td class="text-xs">{{ $TYPE }} TOTAL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right text-xs">{{ number_format($TOTAL, 2) }}</td>
                        </tr>
                        @php
                            $TOTAL = 0;
                        @endphp
                    @endif
                    @php
                        $TYPE = $list->TYPE_NAME;
                    @endphp
                    <tr >
                        <td class="text-center font-weight-bold text-xs">
                            @if (isset($breakDownDate[$row]))
                                {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                            @endif

                            @php
                                $row++;
                            @endphp

                        </td>
                        <td class="text-xs">{{ $list->ITEM_NAME }}</td>
                        <td class="text-xs">{{ $list->UNIT_NAME }}</td>
                        <td class=" text-right text-xs">{{ number_format($list->RATE, 2) }}</td>
                        <td class="text-center text-xs"> {{ $qty }}</td>
                        @php
                            $AMOUNT = $qty * $list->RATE ?? 0;
                            $TOTAL = $TOTAL + $AMOUNT;
                        @endphp
                        <td class="text-right text-xs">{{ number_format($AMOUNT, 2) }}</td>
                    </tr>
                @endforeach

                <tr class="font-weight-bold">
                    <td></td>
                    <td class="text-xs">{{ $TYPE }} TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right text-xs">{{ number_format($TOTAL, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-3">
        <table class="w-100" border="2"  >
            <thead class="text-xs">
                <tr class="text-center">
                    <th class="">ROUTINE MONTHLY LABORATORIES</th>
                </tr>
            </thead>

            <tbody class='text-xs'>
                <tr>
                    <th class="text-center text-xs">(CLINICAL CHEMISTRY)</th>
                </tr>
                <tr>
                    <td class="text-center text-xs">PRE AND POST DIALYSIS BUN</td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> SERUM CREATININE </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> POTASSIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> PHOSPHORUS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> CALCIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> SERUM SODIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> KT/V </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> URR </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> URIC ACID </td>
                </tr>

                <tr>
                    <th class="text-center text-xs" >(HEMATOLOGY) COMPLETE BLOOD COUNT</th>
                </tr>

                <tr>
                    <td class="text-center text-xs">HEMOGLOBIN</td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> HEMATOCRIT</td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> RED BLOOD CELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> MCV </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> MCH </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> MCHC</td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> WHTE BLOODCELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> NEUTROPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> LYMPHOCYTES </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> EOSINOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> BASOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-xs"> PLATELET COUNT </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
