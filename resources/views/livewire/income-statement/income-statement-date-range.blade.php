
<div class="form-group">
    <table class="table table-sm table-bordered table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th class=''>Account</th>
                <th class="text-right">Amount</th>
                <th class="text-right"> Total </th>
            </tr>
        </thead>
        <tbody class="h1">
            @foreach ($dataList as $list)
                <tr>
                    <td
                        class="@if ($list['TYPE'] == 'H') text-sm text-info @endif @if ($list['TYPE'] == 'P') text-sm text-primary @endif">
                        {{ $list['ACCOUNT'] }}</td>
                    <td class="text-right">
                        @if ((float) $list['AMOUNT'] != 0)
                            {{ number_format((float) $list['AMOUNT'], 2) }}
                        @endif

                    </td>
                    <td
                        class="text-right @if ($list['TYPE'] == 'H') text-sm text-info @endif  @if ($list['TYPE'] == 'P') text-sm text-primary @endif @if ($list['TYPE'] == '' && $list['ACCOUNT'] == '' && $list['AMOUNT'] == '') bg-secondary @endif">
                        @if ((float) $list['TOTAL'] != 0)
                            {{ number_format((float) $list['TOTAL'], 2) }}
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
