<div id="printableContent">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }

        .invoice-box {
            max-width: 100%;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(3) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
    </style>


    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                <span class="h3">PULL OUT</span>
                                {{-- <img src="{{ asset('path-to-your-logo.png') }}" style="width:100%; max-width:300px;"> --}}
                            </td>
                            <td>
                                Reference #: {{ $CODE }}<br>
                                Date : {{ date('m/d/Y', strtotime($DATE)) }}<br>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading w-100">
                <td class="col-1">Code</td>
                <td class="col-4 text-left">Item Description</td>
                <td class="col-1 text-center">Unit</td>
                <td class="text-right col-1">Qty</td>
            </tr>

            @foreach ($itemList as $list)
                <tr class="item">
                    <td>{{ $list->CODE }}</td>
                    <td class="text-left">
                        {{ $list->DESCRIPTION }}
                    </td>
                    <td class="text-center">{{ $list->SYMBOL }}</td>
                    <td class="text-right">
                        {{ number_format($list->QUANTITY, 0) }} &nbsp;
                    </td>
                </tr>
            @endforeach

        </table>

        <div class="heading w-100 mt-4">

            <table>
                <tr>
                    <td>
                        <div class="m-2 text-center  bottom-line2">
                            {{ $PREPARED_BY_NAME }}
                        </div>
                        <div class=" text-center">
                            Prepared by
                        </div>
                    </td>
                    <td>
                        <div class="m-2 text-center  bottom-line2">
                            &nbsp;
                        </div>
                        <div class=" text-center">
                            Received by
                        </div>
                    </td>
                </tr>

            </table>


        </div>

    </div>


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
