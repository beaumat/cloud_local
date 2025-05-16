<div>
   
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">LHIO #</th>
                <th class="col-1">Bill Pay Ref#</th>
                <th class="col-1 text-right">Total Patient</th>
                <th class="col-1 text-right">Amount</th>
                <th class="col-1 text-right">Paid</th>
                <th class="col-1 text-right">Wtax</th>
                <th class="col-3">Notes</th>
                <th class="text-center col-1">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
            @endforeach
        </tbody>

    </table>

</div>
