<div class="form-group">
    <table class="table table-sm  table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th>Account Name</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="h1">

            @foreach ($dataList as $list)
                <tr>

                    <td
                        class="@if ($list['ACCOUNT'] == $list['TYPE']) h4 text-primary text-left @endif @if ($list['TYPE'] == 'HEADER') text-info text-md mt-2 font-weight-bold @endif @if ($list['AMOUNT'] == '') pt-1 @endif @if ($list['ORDER'] == 'N') text-md text-primary @endif @if ($list['ORDER'] == 'S') text-md text-success @endif">
                        {{ $list['ACCOUNT'] }}</td>
                    <td
                        class="@if ($list['ACCOUNT'] == $list['TYPE']) h4 text-primary @endif @if ($list['TYPE'] == 'HEADER') text-info text-md pb-2 font-weight-bold @endif  text-right @if ($list['ORDER'] == 'N') text-md text-primary @endif @if ($list['ORDER'] == 'S') text-md text-success @endif">
                        @if ((float) $list['AMOUNT'] != 0)
                            {{ number_format((float) $list['AMOUNT'], 2) }}
                        @endif
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
