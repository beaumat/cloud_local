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
                                        <th>SOA No.</th>
                                        <th>Date Created</th>
                                        <th>Elapsed </th>
                                        <th clsss="bg-success active">LHIO Date</th>
                                        <th clsss="bg-success active">LHIO No.</th>
                                        <th class="col-2">Patients</th>
                                        <th class="text-center">Admitted</th>
                                        <th class="text-center">Discharges</th>
                                        <th class="text-center">Untran. <br /> #Day.</th>
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
                                            <td> {{ Carbon::parse($list->RECORDED_ON)->diffForHumans() }} </td>
                                            <td class="text-left">
                                                @if ($list->AR_DATE)
                                                    {{ date('m/d/Y', strtotime($list->AR_DATE)) }}
                                                @endif
                                            </td>
                                            <td class="text-left">
                                                {{ $list->AR_NO }}
                                            </td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td class="text-center  @if($list->AR_DATE) text-success @else text-danger @endif" >
                                                {{ Carbon::parse($list->DATE_ADMITTED)->diffInDays($list->AR_DATE ?? Carbon::now()) }}
                                            </td>
                                            <td class="text-center"> {{ $list->HEMO_TOTAL }}</td>
                                            <td class="text-right"> {{ number_format($list->P1_TOTAL, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->PAYMENT_AMOUNT, 2) }}</td>
                                            <td
                                                class="@if ($list->STATUS == 'Paid') text-success @else text-danger @endif ">
                                                {{ $list->STATUS }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center">
                                                <a title="View Details"
                                                    href="{{ route('patientsphic_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>
                                                @if ($list->PAYMENT_AMOUNT == 0 && $list->IN_PROGRESS == false && Auth::user()->can('patient.philhealth.print'))
                                                    <span class="btn btn-xs btn-primary" type="button"
                                                        title="Active Print" wire:click='print({{ $list->ID }})'>
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </span>
                                                @else
                                                    <span class="btn btn-xs btn-secondary" type="button"
                                                        title="Active Print">
                                                        <i class="fa fa-print" aria-hidden="true"></i>
                                                    </span>
                                                @endif

                                                @if ($list->PAYMENT_AMOUNT == 0 && $list->IN_PROGRESS == false && Auth::user()->can('patient.philhealth.delete'))
                                                    <span title="Active delete button" type="button"
                                                        wire:click='delete({{ $list->ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </span>
                                                @else
                                                    <span title="Disabled delete button" type="button"
                                                        class="btn btn-xs btn-secondary">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </span>
                                                @endif

                                                <button type="button" title="LHIO Form"
                                                    class="btn btn-success active btn-xs"
                                                    wire:click='getARForm({{ $list->ID }})'>
                                                    <i class="fa fa-registered" aria-hidden="true"></i>
                                                </button>

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
    @livewire('PhilHealth.PrintModal')
</div>
