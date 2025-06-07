<div class="container-fluid">
    <div class="row">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <th>Reference No.</th>
                                <th>Date</th>
                                <th class="text-right">Amount</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-center col-1">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>
                                        <a target="_blank"
                                            href="{{ route('companygeneral_journal_edit', ['id' => $list->ID]) }}">
                                            {{ $list->CODE }}
                                        </a>
                                    </td>
                                    <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                    <td>{{ $list->LOCATION_NAME }}</td>
                                    <td>{{ $list->STATUS }}</td>
                                    <td>
                                        <a target="_blank" class="btn btn-xs btn-primary w-100"
                                            href="{{ route('companygeneral_journal_edit', ['id' => $list->ID]) }}">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
