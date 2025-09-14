<div id="printableContent">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">


                </div>
                <div class="col-12 text-center mt-4 ">
                    <table class="w-100" border="1">
                        <thead>
                            <tr class="bgBlack text-white">
                                <th class="col-1 text-left">Date</th>
                                <th class="col-1 text-left">Type</th>
                                <th class="col-1 text-left">Ref#</th>
                                <th class="col-1 text-left">Location</th>
                                <th>Description</th>
                                <th class="col-1">Debit</th>
                                <th class="col-1">Credit</th>
                                <th class="col-1">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $BALANCE = 0;
                            @endphp
                            @foreach ($dataList as $list)
                                @php
                                    $BALANCE = $BALANCE + $list->AMT;
                                @endphp
                                <tr>
                                    <td> {{ date('M d, Y', strtotime($list->DATE)) }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>{{ $list->CODE }}</td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td>{{ $list->DESCRIPTION }}</td>
                                    <td class="text-right">
                                        @if ($list->ENTRY_TYPE == 0)
                                            {{ number_format($list->AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->ENTRY_TYPE != 0)
                                            {{ number_format($list->AMOUNT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ number_format($BALANCE, 2) }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
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
