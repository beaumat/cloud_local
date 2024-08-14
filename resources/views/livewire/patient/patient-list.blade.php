<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactpatients') }}"> Patients </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">

                            @can('contact.patient.print')
                                <button class="btn btn-success btn-sm" wire:click='export()'>
                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                    Export
                                </button>
                            @endcan

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
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <div class="mt-0">
                                        <label class="text-sm">Search:</label>
                                        <input type="text" wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm" placeholder="Search" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <livewire:select-option name="doctorid" :options="$doctorList" :zero="true"
                                        titleName="Nephro/Doctors :" wire:model.live='doctorid' />
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
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>
                                            <span type="button" wire:click="sorting('contact.ACCOUNT_NO')">No.</span>
                                            @if ($sortby == 'contact.ACCOUNT_NO')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th class="col-1">

                                            <span type="button"
                                                wire:click="sorting('contact.LAST_NAME')">Lastname</span>
                                            @if ($sortby == 'contact.LAST_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="col-1">
                                            <span type="button"
                                                wire:click="sorting('contact.FIRST_NAME')">Firstname</span>
                                            @if ($sortby == 'contact.FIRST_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="col-1">
                                            <span type="button"
                                                wire:click="sorting('contact.MIDDLE_NAME')">Middlename</span>
                                            @if ($sortby == 'contact.MIDDLE_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type="button"
                                                wire:click="sorting('gender_map.DESCRIPTION')">Sex</span>
                                            @if ($sortby == 'gender_map.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th class="text-center">
                                            <span type="button" wire:click="sorting('contact.DATE_OF_BIRTH')">Date of
                                                Birth</span>
                                            @if ($sortby == 'contact.DATE_OF_BIRTH')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type="button" wire:click="sorting('AGE')">Age</span>
                                            @if ($sortby == 'AGE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th class="col-1">
                                            <span type="button" wire:click="sorting('contact.PIN')">Philhealth
                                                No.</span>
                                            @if ($sortby == 'contact.PIN')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="col-2">
                                            <span type="button"
                                                wire:click="sorting('DOCTOR_NAME')">Nephro/Doctors</span>
                                            @if ($sortby == 'DOCTOR_NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th class="col-1 text-center">
                                            <span type="button"
                                                wire:click="sorting('contact.DATE_ADMISSION')">Diagnosis On</span>
                                            @if ($sortby == 'contact.DATE_ADMISSION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th>
                                            <span type="button" wire:click="sorting('l.NAME')">Location</span>
                                            @if ($sortby == 'l.NAME')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-center">
                                            <span type="button"
                                                wire:click="sorting('contact.IS_COMPLETE')">Req.(S)</span>
                                            @if ($sortby == 'contact.IS_COMPLETE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-center">
                                            <span type="button"
                                                wire:click="sorting('contact.INACTIVE')">Inactive</span>
                                            @if ($sortby == 'contact.INACTIVE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-center col-1 bg-success active">
                                            @can('contact.patient.create')
                                                <a href="{{ route('maintenancecontactpatients_create') }}"
                                                    class="text-white">
                                                    <i class="fas fa-plus"></i> New</a>
                                            @endcan

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr class="@if (!$list->IS_COMPLETE) text-secondary @endif">
                                            <td>
                                                <a
                                                    href="{{ route('maintenancecontactpatients_edit', ['id' => $list->ID]) }}">
                                                    {{ $list->ACCOUNT_NO }}
                                                </a>
                                            </td>
                                            <td> {{ $list->LAST_NAME }}</td>
                                            <td> {{ $list->FIRST_NAME }}</td>
                                            <td> {{ $list->MIDDLE_NAME }}</td>
                                            <td> {{ $list->GENDER }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_OF_BIRTH)) }}</td>
                                            <td> {{ $list->AGE }}</td>
                                            <td> {{ $list->PIN }}</td>
                                            <td> {{ $list->DOCTOR_NAME }}</td>
                                            <td class="text-center">
                                                {{ date('m/d/Y', strtotime($list->DATE_ADMISSION)) }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center">
                                                @if ($list->IS_COMPLETE)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td class="text-center font-weight-bold">
                                                @if ($list->INACTIVE)
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a type='button' title="View Details"
                                                    href="{{ route('maintenancecontactpatients_edit', ['id' => $list->ID]) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-eye" aria-hidden="true"></i>
                                                </a>

                                                @can('contact.patient.delete')
                                                    <button type='button' title="Delete Active"
                                                        wire:click='delete({{ $list->ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button type='button' title="Delete Disabled"
                                                        class="btn btn-xs btn-secondary">
                                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                @endcan

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
