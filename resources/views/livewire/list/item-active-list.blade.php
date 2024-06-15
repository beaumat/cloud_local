<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">

                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
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
                                                <select name="location" wire:model.live='LOCATION_ID'
                                                    class="form-control form-control-sm">

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
                                        <th>CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th>TYPE</th>
                                        <th class="text-center">QTY ON-HAND</th>
                                        <th class="text-right">RATE</th>
                                        <th class="text-right">COST</th>

                                        <th class="text-center col-1 bg-success">
                                            <a href="{{ route('maintenanceinventoryitem_create') }}"
                                                class="text-white btn-sm"> <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->CODE }}</td>
                                            <td> {{ $list->DESCRIPTION }}</td>
                                            <td> {{ $list->TYPE }} </td>
                                            <td class="text-center"> {{ number_format($list->QTY_ON_HAND, 0) }}</td>
                                            <td class="text-right">
                                                {{ $list->RATE ? number_format($list->RATE, 2) : '' }}</td>
                                            <td class="text-right">
                                                {{ $list->COST ? number_format($list->COST, 2) : '' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('maintenanceinventoryitem_edit', ['id' => $list->ID]) }}"
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
