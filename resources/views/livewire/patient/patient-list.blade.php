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
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="w-100 form-control form-control-sm" placeholder="Search" />
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>No.</th>
                                  
                                        <th class="col-2">Name</th>
                                        <th>Sex</th>
                                        <th>Date Birth</th>
                                        <th>Age</th>
                                        <th class="col-1">Phic No.</th>
                                        <th class="col-3">Address</th>
                                        <th class="col-1">Mobile No.</th>
                                        <th>Admission</th>
                                        <th class="text-center">Inactive</th>
                                        <th class="text-center col-1">
                                            <a href="{{ route('maintenancecontactpatients_create') }}"
                                                class="text-white">
                                                <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($contacts as $list)
                                        <tr>
                                            <td> {{ $list->ACCOUNT_NO }}</td>
                                          
                                            <td> {{ $list->NAME }}</td>
                                            <td> {{ $list->GENDER }}</td>
                                            <td> {{ \Carbon\Carbon::parse($list->DATE_OF_BIRTH)->format('M/d/Y') }}</td>
                                            <td> {{ $list->AGE }}</td>
                                            <td> {{ $list->TAXPAYER_ID }}</td>
                                            <td> {{ $list->POSTAL_ADDRESS }}</td>
                                            <td> {{ $list->MOBILE_NO }}</td>
                                            <td> {{ \Carbon\Carbon::parse($list->DATE_ADMISSION)->format('M/d/Y') }}</td>
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
            </div>
        </div>
    </section>
</div>
