    <div class="row">
        {{-- @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')]) --}}
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mt-0">
                                        <label class="text-sm">Search:</label>
                                        <input type="text" wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm" placeholder="Search" />
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <th class="col-1">Ref No.</th>
                                <th class="col-1">Date</th>
                                <th class="col-1">Amount</th>
                                <th class="col-1">Balance</th>
                                <th class="col-1">Tax</th>
                                <th class="col-1">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>
                                        <a target="_BLANK" href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                            class="text-primary">
                                            {{ $list->CODE }}
                                        </a>

                                    </td>
                                    <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                 
                                    <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                    <td class="text-right"> {{ number_format($list->BALANCE_DUE, 2) }}</td>
                                    <td> {{ $list->TAX_NAME }}</td>
                                    <td> {{ $list->STATUS }}</td>
                           
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-6">
            {{ $dataList->links() }}
        </div>
    </div>
