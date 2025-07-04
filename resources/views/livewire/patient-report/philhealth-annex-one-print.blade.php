<div class="content-wrapper" id="printableContent">

    <table border="1" class="text-xs">
        <tbody>
            <tr>
                <td></td>
                <td class="col-1"></td>
                <td class="bgBlack"></td>
                <td class="text-center bgBlack text-white" >Patient</td>
                <td class="bgBlack"></td>
                <td class="bgYellow"></td>
                <td class="bgYellow text-center">Member</td>
                <td class="bgYellow"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Item No.</td>
                <td>Claims/Code Reference</td>
                <td class="bgBlack text-white">Surname</td>
                <td class="bgBlack text-white">Firstname</td>
                <td class="bgBlack text-white">Middlename</td>
                <td class="bgYellow">Surname</td>
                <td class="bgYellow">Firstname</td>
                <td class="bgYellow">Middlename</td>
                <td>Member's PIN</td>
                <td>Date of Admission</td>
                <td>Date of Discharged</td>
                <td>Case Rate/ Claim Amount</td>
                <td>ICD 10/RVS code</td>
                <td>*Claim Status</td>
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
                    <td>{{ $list->CLAIM_NO }}</td>
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
                    <td>{{ number_format($list->P1_TOTAL, 2) }}</td>
                    <td>90935</td>
                    <td>FOR FILE</td>
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
