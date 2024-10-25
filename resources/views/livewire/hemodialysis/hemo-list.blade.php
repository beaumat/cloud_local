<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <h5 class="m-0"><a href="{{ route('patientshemo') }}"> Treatment </a></h5>
                </div>
                <div class="col-6 text-right">
                    <div style="float: right;" class="row">
                        @can('patient.treatment.create')
                            @can('patient.treatment.print')
                                <div class="col-3 text-right">
                                    @livewire('Hemodialysis.ScheduleModal', ['LOCATION_ID' => $locationid])
                                </div>
                            @endcan
                        @endcan
                        @can('patient.treatment.print')
                            <div class="col-3">
                                @livewire('Hemodialysis.PrintListModal', ['LOCATION_ID' => $locationid])
                            </div>
                        @endcan
                        <div class="col-3">
                            @livewire('Hemodialysis.HemoUploadFileModal')
                        </div>
                        <div class="col-3">
                            <div>
                                <button title="Export" wire:click="exportData()"
                                    class="btn btn-success btn-sm text-xs mx-2">
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                </button>
                            </div>

                        </div>
                    </div>
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
                                        <div class="col-12 col-md-4">
                                            <div class="mt-0">
                                                <label class="text-xs">
                                                    <a href="#" wire:click='refreshList()'>Search:</a>
                                                </label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="form-control form-control-sm text-xs" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="text-xs">Date From:</label>
                                            <input type="date" class="form-control form-control-sm text-xs"
                                                wire:model.live='DATE_FROM' />
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="text-xs">Date To:</label>
                                            <input type="date" class="form-control form-control-sm text-xs"
                                                wire:model.live='DATE_TO' />
                                        </div>
                                        <div class="col-4 col-md-2">
                                            <div class="mt-0">
                                                <label class="text-xs">Status:</label>
                                                <select name="location" wire:model.live='statusid'
                                                    class="form-control form-control-sm text-xs">
                                                    <option value="0"> All Status</option>
                                                    @foreach ($statusList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->DESCRIPTION }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-8 col-md-2">
                                            <div class="mt-0">
                                                <label class="text-xs">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity:
                                                    0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm text-xs">
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
                            <div style="max-height: 73vh; overflow-y: auto;" class="border">
                                <div style="width:1500px;max-width:1900px;">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="text-xs bg-sky sticky-header">
                                            <tr>
                                                <th>Ref No.</th>
                                                <th>Date</th>
                                                <th class="col-2">Patient</th>
                                                <th class="text-center ">W</th>
                                                <th class="text-center ">B.P</th>
                                                <th class="text-center ">H.R</th>
                                                <th class="text-center ">O2(S)</th>
                                                <th class="text-center ">TMP</th>
                                                <th class="text-center ">Start</th>
                                                <th class="text-center ">End</th>
                                                <th class="text-center">IC</th>
                                                <th class="col-2">Nurse Encoded</th>
                                                {{-- <th class="text-center">JTF</th> --}}
                                                <th>Location</th>
                                                <th class="text-center">S</th>
                                                <th class="text-center"> SC</th>
                                                <th class="text-center text-center">
                                                    Action
                                                    {{-- @can('patient.treatment.create')
                                                <a href="{{ route('patientshemo_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            @endcan --}}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs bg-light">
                                            @foreach ($pendingList as $list)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}"
                                                            class="text-primary">
                                                            {{ $list->CODE }}
                                                        </a>
                                                    </td>
                                                    <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                                    <td> {{ $list->CONTACT_NAME }}</td>
                                                    <td class="text-center">
                                                        {{ $list->PRE_WEIGHT }} | {{ $list->POST_WEIGHT }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list->PRE_BLOOD_PRESSURE }}/{{ $list->PRE_BLOOD_PRESSURE2 }}
                                                        |
                                                        {{ $list->POST_BLOOD_PRESSURE }}/{{ $list->POST_BLOOD_PRESSURE2 }}
                                                    </td>
                                                    <td class="text-center"> {{ $list->PRE_HEART_RATE }} |
                                                        {{ $list->POST_HEART_RATE }}</td>
                                                    <td class="text-center"> {{ $list->PRE_O2_SATURATION }} |
                                                        {{ $list->POST_O2_SATURATION }}</td>
                                                    <td class="text-center"> {{ $list->PRE_TEMPERATURE }} |
                                                        {{ $list->POST_TEMPERATURE }}</td>
                                                    <td class="text-center">
                                                        @if ($list->TIME_START)
                                                            {{ \Carbon\Carbon::parse($list->TIME_START)->format('h:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($list->TIME_END)
                                                            {{ \Carbon\Carbon::parse($list->TIME_END)->format('h:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($list->IS_INCOMPLETE)
                                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                        @else
                                                            <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                        @endif
                                                    </td>
                                                    <td>{{ $list->NURSE_NAME }}</td>
                                                    {{-- <td class="text-center">
                                                @if ($list->JUSTIFY)
                                                    <i class="fa fa-check text-primary" aria-hidden="true"></i>
                                                @endif
                                            </td> --}}
                                                    <td> {{ $list->LOCATION_NAME }} </td>
                                                    <td
                                                        class="text-center @if ($list->STATUS_ID == 1) bg-warning  @elseif ($list->STATUS_ID == 2) bg-success  @elseif ($list->STATUS_ID == 4) bg-secondary @else bg-danger @endif ">
                                                        {{ substr($list->STATUS, 0, 1) }} </td>
                                                    <td class="text-center">
                                                        @if ($list->IS_SC)
                                                            <i class="fa fa-check text-success" aria-hidden="true"></i>
                                                        @else
                                                            <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button"
                                                            href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}"
                                                            class="btn btn-xs btn-info">
                                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                        @can('patient.treatment.delete')
                                                            @if ($list->STATUS_ID == 1)
                                                                <a href="#"
                                                                    wire:click='delete({{ $list->ID }})'
                                                                    wire:confirm="Are you sure you want to delete this?"
                                                                    class="btn btn-xs btn-danger">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                            @else
                                                                <a class="btn btn-xs btn-secondary">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @can('patient.treatment.print')
                                                            <a target="_blank" type="button"
                                                                href="{{ route('patientshemo_print', ['id' => $list->ID]) }}"
                                                                class="btn btn-xs btn-primary">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                            </a>

                                                            @if ($list->FILE_PATH)
                                                                <a type="button"
                                                                    href="{{ asset('storage/' . $list->FILE_PATH) }}"
                                                                    target="_blank" class="btn btn-xs btn-danger">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                </a>
                                                            @else
                                                                <button type="button" class="btn btn-xs btn-secondary">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        @endcan

                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                        {{-- end of pending --}}
                                        <tbody class="text-xs">
                                            @foreach ($dataList as $list)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}"
                                                            class="text-primary">
                                                            {{ $list->CODE }}
                                                        </a>
                                                    </td>
                                                    <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                                    <td>
                                                        <a target="_BLANK" class="text-primary"
                                                            href="{{ route('maintenancecontactpatients_edit', ['id' => $list->PATIENT_ID]) }}">
                                                            {{ $list->CONTACT_NAME }}</a>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list->PRE_WEIGHT }} | {{ $list->POST_WEIGHT }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $list->PRE_BLOOD_PRESSURE }}/{{ $list->PRE_BLOOD_PRESSURE2 }}
                                                        |
                                                        {{ $list->POST_BLOOD_PRESSURE }}/{{ $list->POST_BLOOD_PRESSURE2 }}
                                                    </td>
                                                    <td class="text-center"> {{ $list->PRE_HEART_RATE }} |
                                                        {{ $list->POST_HEART_RATE }}</td>
                                                    <td class="text-center"> {{ $list->PRE_O2_SATURATION }} |
                                                        {{ $list->POST_O2_SATURATION }}</td>
                                                    <td class="text-center"> {{ $list->PRE_TEMPERATURE }} |
                                                        {{ $list->POST_TEMPERATURE }}</td>
                                                    <td class="text-center">
                                                        @if ($list->TIME_START)
                                                            {{ \Carbon\Carbon::parse($list->TIME_START)->format('h:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($list->TIME_END)
                                                            {{ \Carbon\Carbon::parse($list->TIME_END)->format('h:i A') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($list->IS_INCOMPLETE)
                                                            <i class="fa fa-check text-success"
                                                                aria-hidden="true"></i>
                                                        @else
                                                            <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                        @endif
                                                    </td>
                                                    <td>{{ $list->NURSE_NAME }}</td>
                                                    {{-- <td class="text-center">
                                                @if ($list->JUSTIFY)
                                                    <i class="fa fa-check text-primary" aria-hidden="true"></i>
                                                @endif
                                            </td> --}}
                                                    <td> {{ $list->LOCATION_NAME }} </td>
                                                    <td
                                                        class="text-center @if ($list->STATUS_ID == 1) bg-warning  @elseif ($list->STATUS_ID == 2) bg-success  @elseif ($list->STATUS_ID == 4) bg-secondary @else bg-danger @endif ">
                                                        {{ substr($list->STATUS, 0, 1) }} </td>
                                                    <td class="text-center">
                                                        @if ($list->IS_SC)
                                                            <i class="fa fa-check text-success"
                                                                aria-hidden="true"></i>
                                                        @else
                                                            <i class="fa fa-times text-danger" aria-hidden="true"></i>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <a type="button"
                                                            href="{{ route('patientshemo_edit', ['id' => $list->ID]) }}"
                                                            class="btn btn-xs btn-info">
                                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                        @can('full-treatment-sheet')
                                                            <button type="button" class="btn btn-xs btn-dark"
                                                                wire:click="showNotes({{ $list->ID }},'{{ $list->CONTACT_NAME }}')">
                                                                <i class="fa fa-list-ol" aria-hidden="true"></i>
                                                            </button>
                                                        @endcan
                                                        @can('patient.treatment.delete')
                                                            @if ($list->STATUS_ID == 1)
                                                                <button wire:click='delete({{ $list->ID }})'
                                                                    wire:confirm="Are you sure you want to delete this?"
                                                                    class="btn btn-xs btn-danger">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-xs btn-secondary">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        @endcan

                                                        @can('patient.treatment.print')
                                                            <a target="_blank" type="button"
                                                                href="{{ route('patientshemo_print', ['id' => $list->ID]) }}"
                                                                class="btn btn-xs btn-primary">
                                                                <i class="fa fa-print" aria-hidden="true"></i>
                                                            </a>

                                                            @if ($list->FILE_PATH)
                                                                <a type="button"
                                                                    href="{{ asset('storage/' . $list->FILE_PATH) }}"
                                                                    target="_blank" class="btn btn-xs btn-danger">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                </a>
                                                            @else
                                                                <button type="button" class="btn btn-xs btn-secondary">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                </button>
                                                            @endif
                                                        @endcan
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
                <div class="col-6 col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
    @livewire('Hemodialysis.NurseNotes')
</div>
