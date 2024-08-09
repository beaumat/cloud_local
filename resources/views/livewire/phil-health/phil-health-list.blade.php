<div class="content-wrapper">
    @php
        use Carbon\Carbon;
    @endphp
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
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='locationid'
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
                                        <th>Date Created</th>
                                        <th>Elapsed </th>
                                        <th clsss="bg-success">AR No.</th>
                                        <th clsss="bg-success">AR Date</th>
                                 
                                        <th class="col-2">Patients</th>
                                        <th class="text-center">Admitted</th>
                                        <th class="text-center">Discharges</th>
                                        <th class="text-center">#Trmt. </th>
                                        <th class='text-right'>FC Amt.</th>
                                        <th class="text-right">Paid Amt.</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th class="text-center col-2 bg-success">
                                            @can('patient.philhealth.create')
                                                <a href="{{ route('patientsphic_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            @endcan
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
                                            <td> {{ Carbon::parse($list->DATE)->diffForHumans() }} </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn @if ($list->AR_NO) btn-white @else btn-secondary @endif btn-xs w-100"
                                                    wire:click='getARForm({{ $list->ID }})'>
                                                    @if ($list->AR_NO)
                                                        {{ $list->AR_NO }}
                                                    @else
                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                    @endif
                                                    </span>
                                            </td>
                                            <td class="text-center">
                                                <span type="button" wire:click='getARForm({{ $list->ID }})'>
                                                    @if ($list->AR_DATE)
                                                        {{ date('m/d/Y', strtotime($list->AR_DATE)) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                            <td class="text-right"> {{ number_format($list->P1_TOTAL, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->PAYMENT_AMOUNT, 2) }}</td>
                                            <td
                                                class=" @if ($list->STATUS == 'Paid') text-success @else text-danger @endif ">
                                                {{ $list->STATUS }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>


                                            <td class="text-center">
                                                @can('patient.philhealth.print')
                                                    <a target="_BLANK" title="Soa"
                                                        href="{{ route('patientsphic_print', ['id' => $list->ID]) }}"
                                                        class="btn-sm text-primary"> <i class="fa fa-file-pdf-o"
                                                            aria-hidden="true"></i></a>
                                                    <a target="_BLANK" title="Philheath Form"
                                                        href="{{ route('patientsphic_print_form', ['id' => $list->ID]) }}"
                                                        class="btn-sm text-danger"> <i class="fa fa-file-pdf-o"
                                                            aria-hidden="true"></i> </a>
                                                @endcan

                                                <a href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="btn-sm text-info">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </a>
                                                @if ($list->PAYMENT_AMOUNT == 0)
                                                    @can('patient.philhealth.delete')
                                                        <a href="#" wire:click='delete({{ $list->ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn-sm text-danger">
                                                            <i class="fas fa-times" aria-hidden="true"></i>
                                                        </a>
                                                    @endcan
                                                @endif
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
    @livewire('PhilHealth.ArForm')
</div>
