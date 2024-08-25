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
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' =>
                session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" wire:model.live.debounce.150ms='search'
                                        class="form-control form-control-sm text-xs mb-1 bg-light"
                                        placeholder="Search" />
                                </div>
                                <div class="col-md-8 text-right">
                                    <h5 class="m-0">
                                        <a href="{{ route('maintenanceinventoryitem') }}"> Items </a>
                                    </h5>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Type</th>

                                        <th>Class</th>
                                        <th>Sub-Class</th>
                                        <th>UOM</th>
                                        <th class="text-right">Rate</th>
                                        <th class="text-right">Cost</th>
                                        <th class="text-center">N.H</th>
                                        <th class="text-center">N.H.Inv</th>
                                        <th class="text-center">Tax</th>
                                        <th class="text-center">Inact.</th>
                                        <th class="text-center col-1 bg-success">
                                            @can('items.create')
                                            <a href="{{ route('maintenanceinventoryitem_create') }}"
                                                class="text-white btn-sm"> <i class="fas fa-plus"></i> New
                                            </a>
                                            @endcan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($items as $list)
                                    <tr>
                                        <td>
                                            <a href="{{ route('maintenanceinventoryitem_edit', ['id' => $list->ID]) }}">
                                                {{ $list->CODE }}
                                            </a>
                                        </td>
                                        <td> {{ $list->DESCRIPTION }}</td>
                                        <td> {{ $list->ITEM_TYPE }} </td>
                                        <td> {{ $list->CLASS }}</td>
                                        <td> {{ $list->SUB_CLASS }}</td>
                                        <td> {{ $list->UNIT_BASE }}</td>
                                        <td class="text-right">
                                            {{ $list->RATE ? number_format($list->RATE, 2) : '' }}</td>
                                        <td class="text-right">
                                            {{ $list->COST ? number_format($list->COST, 2) : '' }}</td>
                                        <td class="text-center"> {{ $list->NON_HEMO ? 'Yes' : 'No' }} </td>
                                        <td class="text-center"> {{ $list->HEMO_NON_INVENTORY ? 'Yes' : 'No' }} </td>
                                        <td class="text-center"> {{ $list->TAXABLE ? 'Yes' : 'No' }} </td>
                                        <td class="text-center"> {{ $list->INACTIVE ? 'Yes' : 'No' }}</td>
                                        <td class="text-center">
                                            <a title="View Details"
                                                href="{{ route('maintenanceinventoryitem_edit', ['id' => $list->ID]) }}"
                                                class="btn btn-xs btn-info">
                                                <i class="fas fa-eye" aria-hidden="true"></i>
                                            </a>

                                            @can('items.delete')
                                            <button type="button" wire:click='delete({{ $list->ID }})'
                                                wire:confirm="Are you sure you want to delete this?"
                                                class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash" aria-hidden="true"></i>
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-xs btn-secondary">
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
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </section>




</div>