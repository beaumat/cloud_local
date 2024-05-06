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
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>ID</th>
                                        <th class="col-2">PATIENT NAME</th>
                                        <th>SEX</th>
                                        <th class="col-1">DATE OF BIRTH</th>
                                        <th>AGE</th>
                                        <th class="col-1">PHILHEALTH NO.</th>
                                        <th class="col-1">MOBILE NO.</th>
                                        <th class="col-1">ADMISSION</th>
                                        <th class="col-1">LOCATION</th>
                                        <th class="text-center">REQ. STATUS</th>
                                        <th class="text-center">INACTIVE</th>
                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('maintenancecontactpatients_create') }}"
                                                class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr class="@if (!$list->IS_COMPLETE) text-secondary @endif">
                                            <td> {{ $list->ACCOUNT_NO }}</td>
                                            <td> {{ $list->NAME }}</td>
                                            <td> {{ $list->GENDER }}</td>
                                            <td> {{ \Carbon\Carbon::parse($list->DATE_OF_BIRTH)->format('M/d/Y') }}</td>
                                            <td> {{ $list->AGE }}</td>
                                            <td> {{ $list->PIN }}</td>
                                            <td> {{ $list->MOBILE_NO }}</td>
                                            <td> {{ \Carbon\Carbon::parse($list->DATE_ADMISSION)->format('M/d/Y') }}
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center">
                                                @if ($list->IS_COMPLETE)
                                                    <strong class="text-success">Completed</strong>
                                                @else
                                                    <strong class="text-secondary">Uncompleted</strong>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($list->INACTIVE)
                                                    <strong class="text-danger">Yes</strong>
                                                @else
                                                    <strong class="text-primary">No</strong>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenancecontactpatients_edit', ['id' => $list->ID]) }}"
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
