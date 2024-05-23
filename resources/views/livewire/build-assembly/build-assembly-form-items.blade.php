<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Code</th>
                <th class="col-5">Description</th>
                <th class="col-1">Qty</th>
                <th class="col-1">Rate</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->DESCRIPTION }}</td>
                    <td class="text-right">
                         {{ number_format($list->QUANTITY, 0) }}
                    </td>
                    <td class="text-right">
                            {{ number_format($list->AMOUNT, 2) }}
                    </td>                                       
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
