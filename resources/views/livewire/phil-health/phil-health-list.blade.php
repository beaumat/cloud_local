<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsphic') }}"> PhilHealth </a></h5>
                </div>
                <div class="col-sm-6 text-right">
                    @livewire('PhilHealth.QuickCreate')
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
                                        <div class="col-md-9">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                        <th>No.</th>
                                        <th class="col-1">Date</th>
                                        <th class="col-3">Patients</th>
                                        <th class="col-1">Admitted On</th>
                                        <th class="col-1">Discharges ON</th>
                                        <th class="col-1">No. of Treatment </th>
                                        <th class="col-1">Total Charge</th>
                                        <th class="col-1">Location</th>
                                        <th class="col-1">Status</th>
                                        <th class="text-center col-2 bg-success">
                                            <a href="{{ route('patientsphic_create') }}"
                                                class="text-white btn btn-xs w-100">
                                                <i class="fas fa-plus"></i> New
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary">
                                                    {{ $list->CODE }}
                                                </a>
                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                            <td class="text-right"> {{ number_format($list->CHARGE_TOTAL, 2) }}</td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td> {{ $list->STATUS }}</td>
                                            <td class="text-center">
                                                {{-- @if ($list->FILE_PATH)
                                                    <a href="{{ asset('storage/' . $list->FILE_PATH) }}"
                                                        target="_blank" class="btn-sm text-danger">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <a href="#" class="btn-sm text-secondary">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                    </a>
                                                @endif --}}


                                                <a target="_BLANK" title="Computation"
                                                    href="{{ route('patientsphic_print', ['id' => $list->ID]) }}"
                                                    class="btn-sm text-primary"> <i class="fa fa-file-pdf-o"
                                                        aria-hidden="true"></i></a>

                                                <a target="_BLANK" title="Philheath Form"
                                                    href="{{ route('patientsphic_print_form', ['id' => $list->ID]) }}"
                                                    class="btn-sm text-danger"> <i class="fa fa-file-pdf-o"
                                                        aria-hidden="true"></i> </a>

                                                <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="btn-sm text-info">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                <a href="#" wire:click='delete({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="btn-sm text-danger">
                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                </a>
                                            </td>
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
