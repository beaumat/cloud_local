<div class="mt-1 row">
    <div class="col-9">
        <table class="w-100 table" border="2">
            <thead>
                <tr class="text-center">
                    <th>SERVICE DATE</th>
                    <th>ITEM NAME</th>
                    <th>UNIT OF <br />MEASUREMENT</th>
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
                            <td class="text-center">
                                @if (isset($breakDownDate[$row]))
                                    {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                                @endif

                                @php
                                    $row++;
                                @endphp
                            </td>
                            <td>{{ $TYPE }} TOTAL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ number_format($TOTAL, 2) }}</td>
                        </tr>
                        @php
                            $TOTAL = 0;
                        @endphp
                    @endif
                    @php
                        $TYPE = $list->TYPE_NAME;
                    @endphp
                    <tr>
                        <td class="text-center font-weight-bold">
                            @if (isset($breakDownDate[$row]))
                                {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                            @endif

                            @php
                                $row++;
                            @endphp

                        </td>
                        <td>{{ $list->ITEM_NAME }}</td>
                        <td>{{ $list->UNIT_NAME }}</td>
                        <td class=" text-right">{{ number_format($list->RATE, 2) }}</td>
                        <td class="text-center"> {{ $qty }}</td>
                        @php
                            $AMOUNT = $qty * $list->RATE ?? 0;
                            $TOTAL = $TOTAL + $AMOUNT;
                        @endphp
                        <td class="text-right">{{ number_format($AMOUNT, 2) }}</td>
                    </tr>
                @endforeach

                <tr class="font-weight-bold">
                    <td></td>
                    <td>{{ $TYPE }} TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ number_format($TOTAL, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-3">
        <table class="w-100 table text-xs" border="2">
            <thead>
                <tr class="text-center">
                    <th>ROUTINE MONTHLY LABORATORIES</th>
                </tr>
            </thead>

            <tbody class=''>
                <tr>
                    <th class="text-center">(CLINICAL CHEMISTRY)</th>
                </tr>
                <tr>
                    <td class="text-center">PRE AND POST DIALYSIS BUN</td>
                </tr>
                <tr>
                    <td class="text-center"> SERUM CREATININE </td>
                </tr>
                <tr>
                    <td class="text-center"> POTASSIUM </td>
                </tr>
                <tr>
                    <td class="text-center"> PHOSPHORUS </td>
                </tr>
                <tr>
                    <td class="text-center"> CALCIUM </td>
                </tr>
                <tr>
                    <td class="text-center"> SERUM SODIUM </td>
                </tr>
                <tr>
                    <td class="text-center"> KT/V </td>
                </tr>
                <tr>
                    <td class="text-center"> URR </td>
                </tr>
                <tr>
                    <td class="text-center"> URIC ACID </td>
                </tr>

                <tr>
                    <th class="text-center">(HEMATOLOGY) COMPLETE BLOOD COUNT</th>
                </tr>

                <tr>
                    <td class="text-center">HEMOGLOBIN</td>
                </tr>
                <tr>
                    <td class="text-center"> HEMATOCRIT</td>
                </tr>
                <tr>
                    <td class="text-center"> RED BLOOD CELLS </td>
                </tr>
                <tr>
                    <td class="text-center"> MCV </td>
                </tr>
                <tr>
                    <td class="text-center"> MCH </td>
                </tr>
                <tr>
                    <td class="text-center"> MCHC</td>
                </tr>
                <tr>
                    <td class="text-center"> WHTE BLOODCELLS </td>
                </tr>
                <tr>
                    <td class="text-center"> NEUTROPHILS </td>
                </tr>
                <tr>
                    <td class="text-center"> LYMPHOCYTES </td>
                </tr>
                <tr>
                    <td class="text-center"> EOSINOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center"> BASOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center"> PLATELET COUNT </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
