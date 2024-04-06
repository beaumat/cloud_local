<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('transactionshemo') }}"> Hemodialysis Treatment </a></h5>
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
                                        <th>REF NO.</th>
                                        <th>DATE</th>
                                        <th class="col-md-4">PATIENT NAME</th>
                                        <th class="text-center">W</th>
                                        <th class="text-center">B.P</th>
                                        <th class="text-center">H.R</th>
                                        <th class="text-center">O2(S)</th>
                                        <th class="text-center">TMP</th>
                                        <th class="col-2">Location</th>
                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('transactionshemo_create') }}"
                                                class="text-white btn btn-xs w-100">
                                                <i class="fas fa-plus"></i> New
                                            </a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->CODE }}</td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td class="text-center">
                                                {{ $list->PRE_WEIGHT }} | {{ $list->POST_WEIGHT }}
                                            </td>
                                            <td class="text-center"> {{ $list->PRE_BLOOD_PRESSURE }} |
                                                {{ $list->POST_BLOOD_PRESSURE }}</td>
                                            <td class="text-center"> {{ $list->PRE_HEART_RATE }} |
                                                {{ $list->POST_HEART_RATE }}</td>
                                            <td class="text-center"> {{ $list->PRE_O2_SATURATION }} |
                                                {{ $list->POST_O2_SATURATION }}</td>
                                            <td class="text-center"> {{ $list->PRE_TEMPERATURE }} |
                                                {{ $list->POST_TEMPERATURE }}</td>
                                            <td> {{ $list->LOCATION_NAME }} </td>

                                            <td class="text-center">
                                                <a href="#" class="btn-sm text-primary">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                </a>
                                                <a href="{{ route('transactionshemo_edit', ['id' => $list->ID]) }}"
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
