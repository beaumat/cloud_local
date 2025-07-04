<div class="content-wrapper" id="printableContent">

    <table border="1" class="text-xs">
        <tbody>
            <tr>
                <td class=""></td>
                <td class="col-1"></td>
                <td class="col-1"></td>
                <td class=" "></td>
                <td class=" text-center">Patient</td>
                <td class=""></td>
                <td class=""></td>
                <td class=" text-center">Member</td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
                <td class=""></td>
            </tr>
            <tr>
                <td class=" bg-dark">Item No.</td>
                <td class="">Yr. Start From.</td>
                <td class="">Claims Series Reference</td>
                <td class=" ">Surname</td>
                <td class=" ">Firstname</td>
                <td class=" ">Middlename</td>
                <td class="">Surname</td>
                <td class=" ">Firstname</td>
                <td class=" ">Middlename</td>
                <td class="">Member's PIN</td>
                <td class="">Date of Admission</td>
                <td class="">Date of Discharged</td>
                <td class="">Date of Filed</td>
                <td class="">Date of Refiled</td>
                <td class="">ICD 10/RVS code</td>
                <td class="">Case Rate/ Claim Amount</td>
                <td class="">*Claim Status</td>
            </tr>

            @php

                $r = 0;
            @endphp
            @foreach ($dataList as $list)
                @php
                    $r++;

                @endphp
                <tr>
                    <td>{{ $r }}</td>
                    <td>{{ $YEAR }}</td>
                    <td>{{ $list->AR_NO }}</td>
                    <td>{{ $list->LAST_NAME }}</td>
                    <td>{{ $list->FIRST_NAME }}</td>
                    <td>{{ $list->MIDDLE_NAME }}</td>
                    @if ($list->IS_PATIENT)
                        <td>{{ $list->LAST_NAME }}</td>
                        <td>{{ $list->FIRST_NAME }}</td>
                        <td>{{ $list->MIDDLE_NAME }}</td>
                    @else
                        <td>{{ $list->MEMBER_LAST_NAME }}</td>
                        <td>{{ $list->MEMBER_FIRST_NAME }}</td>
                        <td>{{ $list->MEMBER_MIDDLE_NAME }}</td>
                    @endif

                    <td>{{ $list->PIN_NO }}</td>
                    <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                    <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                    <td>{{ date('M/d/Y', strtotime($list->AR_DATE)) }}</td>
                    <td>N/A</td>
                    <td>90935</td>
                    <td>{{ number_format($list->P1_TOTAL, 2) }}</td>
                    <td>In Progress</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>

@script
    <script>
        $wire.on('print', () => {
            var printContents = document.getElementById('printableContent').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        });

        function printPageAndClose() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 100);
        }

        window.addEventListener('beforeprint', function() {
            printPageAndClose();
        });
    </script>
@endscript
