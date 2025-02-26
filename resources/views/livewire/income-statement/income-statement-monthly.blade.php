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
                <tr class="@if($list['ACCOUNT_TYPE'] == '') font-weight-bold text-danger @endif">
                    <td> &nbsp; {{ $list['ACCOUNT_NAME'] }}</td>
                    <td class="text-right"> {{ $list['JAN'] }}</td>
                    <td class="text-right"> {{ $list['FEB'] }}</td>
                    <td class="text-right"> {{ $list['MAR'] }}</td>
                    <td class="text-right"> {{ $list['APR'] }}</td>
                    <td class="text-right"> {{ $list['MAY'] }}</td>
                    <td class="text-right"> {{ $list['JUN'] }}</td>
                    <td class="text-right"> {{ $list['JUL'] }}</td>
                    <td class="text-right"> {{ $list['AUG'] }}</td>
                    <td class="text-right"> {{ $list['SEP'] }}</td>
                    <td class="text-right"> {{ $list['OCT'] }}</td>
                    <td class="text-right"> {{ $list['NOV'] }}</td>
                    <td class="text-right"> {{ $list['DEC'] }}</td>
                    <td class="text-right"> {{ $list['TOTAL'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
