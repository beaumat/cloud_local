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
                            <td class="text-center text-sm">
                                @if (isset($breakDownDate[$row]))
                                    {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                                @endif

                                @php
                                    $row++;
                                @endphp
                            </td>
                            <td class="text-sm">{{ $TYPE }} TOTAL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right text-sm">{{ number_format($TOTAL, 2) }}</td>
                        </tr>
                        @php
                            $TOTAL = 0;
                        @endphp
                    @endif
                    @php
                        $TYPE = $list->TYPE_NAME;
                    @endphp
                    <tr >
                        <td class="text-center font-weight-bold text-sm">
                            @if (isset($breakDownDate[$row]))
                                {{ date('M/d/Y', strtotime($breakDownDate[$row]['DATE'])) }}
                            @endif

                            @php
                                $row++;
                            @endphp

                        </td>
                        <td class="text-sm">{{ $list->ITEM_NAME }}</td>
                        <td class="text-sm">{{ $list->UNIT_NAME }}</td>
                        <td class=" text-right text-sm">{{ number_format($list->RATE, 2) }}</td>
                        <td class="text-center text-sm"> {{ $qty }}</td>
                        @php
                            $AMOUNT = $qty * $list->RATE ?? 0;
                            $TOTAL = $TOTAL + $AMOUNT;
                        @endphp
                        <td class="text-right text-sm">{{ number_format($AMOUNT, 2) }}</td>
                    </tr>
                @endforeach

                <tr class="font-weight-bold">
                    <td></td>
                    <td class="text-sm">{{ $TYPE }} TOTAL</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right text-sm">{{ number_format($TOTAL, 2) }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="col-3">
        <table class="w-100 table" border="2">
            <thead>
                <tr class="text-center">
                    <th class="text-sm">ROUTINE MONTHLY LABORATORIES</th>
                </tr>
            </thead>

            <tbody class='text-sm'>
                <tr>
                    <th class="text-center text-sm">(CLINICAL CHEMISTRY)</th>
                </tr>
                <tr>
                    <td class="text-center text-sm">PRE AND POST DIALYSIS BUN</td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> SERUM CREATININE </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> POTASSIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> PHOSPHORUS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> CALCIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> SERUM SODIUM </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> KT/V </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> URR </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> URIC ACID </td>
                </tr>

                <tr>
                    <th class="text-center text-sm" >(HEMATOLOGY) COMPLETE BLOOD COUNT</th>
                </tr>

                <tr>
                    <td class="text-center text-sm">HEMOGLOBIN</td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> HEMATOCRIT</td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> RED BLOOD CELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> MCV </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> MCH </td>
                </tr>
                <tr>
                    <td class="text-center"> MCHC</td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> WHTE BLOODCELLS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> NEUTROPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> LYMPHOCYTES </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> EOSINOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> BASOPHILS </td>
                </tr>
                <tr>
                    <td class="text-center text-sm"> PLATELET COUNT </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
