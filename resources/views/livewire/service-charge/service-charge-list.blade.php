<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsservice_charges') }}"> Service Charges </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mt-0">
                                                <label class="text-sm"> <a href="#"
                                                        wire:click='refreshList()'>Search:</a> </label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class ="col-md-2">
                                            <label class="text-sm">Date From:</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model.live='DATE_FROM' />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="text-sm">Date To:</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model.live='DATE_TO' />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm">
                                                    <option value="0"> All Location</option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Ref No.</th>
                                        <th class="col-1">Date</th>
                                        <th class="col-4">Patients</th>
                                        <th class="col-1">Location</th>
                                        <th class="col-1">Amount</th>
                                        <th class="col-1">Balance</th>
                                        {{-- <th class="col-1">Tax</th> --}}
                                        <th class=" col-1 text-center">Status</th>
                                        <th class=" col-1 text-center">Treatment</th>
                                        @can('patient.service-charges.create')
                                            <th class="text-center col-1 bg-primary">
                                                <a href="{{ route('patientsservice_charges_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary">
                                                    {{ $list->CODE }}
                                                </a>

                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->BALANCE_DUE, 2) }}</td>
                                            {{-- <td> {{ $list->TAX_NAME }}</td> --}}
                                            <td
                                                class="text-center
                                            @if ($list->STATUS_ID == 0) bg-warning @elseif ($list->STATUS_ID == 2) bg-primary  @elseif ($list->STATUS_ID == 11) bg-success @else bg-secondary @endif
                                            ">
                                                {{ $list->STATUS }}</td>
                                            <td
                                                class="text-center @if ($list->TR_STATUS == 'Draft') bg-warning  @elseif ($list->TR_STATUS == 'Posted') bg-success  @elseif ($list->TR_STATUS == 'Unposted') bg-secondary @else bg-danger @endif ">
                                                {{ $list->TR_STATUS }}</td>
                                            @can('patient.service-charges.create')
                                                <td class="text-center">
                                                    <a href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}"
                                                        class="btn-sm text-info">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    @if ($list->STATUS_ID == 0)
                                                        @can('patient.service-charges.delete')
                                                            <a href="#" wire:click='delete({{ $list->ID }})'
                                                                wire:confirm="Are you sure you want to delete this?"
                                                                class="btn-sm text-danger">
                                                                <i class="fas fa-times" aria-hidden="true"></i>
                                                            </a>
                                                        @endcan
                                                    @else
                                                    @endif
                                                </td>
                                            @endcan
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
        </div>
    </section>
</div>
