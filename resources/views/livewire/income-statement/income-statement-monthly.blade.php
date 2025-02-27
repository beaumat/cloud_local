<div class="form-group">
    <table class="table table-sm table-bordered table-hover ">
        <thead class="bg-sky h1">
            <tr>
                <th class='col-4'>Account</th>
                <th class=''>Jan</th>
                <th class=''>Feb</th>
                <th class=''>Mar</th>
                <th class=''>Apr</th>
                <th class=''>May</th>
                <th class=''>Jun</th>
                <th class=''>Jul</th>
                <th class=''>Aug</th>
                <th class=''>Sep</th>
                <th class=''>Oct</th>
                <th class=''>Nov</th>
                <th class=''>Dec</th>
                <th class=''>Total</th>
            </tr>
        </thead>
        <tbody class="h1">

            @foreach ($dataList as $list)
                <tr class="@if ($list['ACCOUNT_TYPE'] == '') font-weight-bold text-danger @endif">
                    <td> &nbsp; {{ $list['ACCOUNT_NAME'] }}</td>
                    <td class="text-right"> <a href="#" target="_blank"> {{ $list['JAN'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['FEB'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['MAR'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['APR'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['MAY'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['JUN'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['JUL'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['AUG'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['SEP'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['OCT'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['NOV'] }}</a></td>
                    <td class="text-right"> <a href="#" target="_blank">{{ $list['DEC'] }}</a></td>
                    <td class="text-right">{{ $list['TOTAL'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
