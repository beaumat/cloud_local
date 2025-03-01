<div class="form-group">
    <table class="table table-sm table-bordered table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th class='col-4'>Account</th>
                <th class=''>Total</th>
            </tr>
        </thead>
        <tbody class="h1">

            @foreach ($dataList as $list)
                <tr
                    class="@if ($list['ACCOUNT_TYPE'] == 'total') font-weight-bold text-danger @elseif ($list['ACCOUNT_TYPE'] == 'grand') font-weight-bold text-info @endif">
                    <td> &nbsp; {{ $list['ACCOUNT_NAME'] }}</td>
                    <td class="text-right">{{ $list['TOTAL'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
