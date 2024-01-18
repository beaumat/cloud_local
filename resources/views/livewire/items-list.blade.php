<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceinventoryitem') }}"> Items </a>
                    </h5>
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
                <div class="col-12">

                    @if (session()->has('message'))
                        <div class="pt-1 pb-1 text-sm alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="pt-1 pb-1 text-sm alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                </div>
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
                                <thead class="text-sm bg-sky">
                                    <tr>
                                        <th>CODE</th>
                                        <th>DESCRIPTION</th>
                                        <th>TYPE</th>
                                        <th class="text-center">TAX</th>
                                        <th>GROUP</th>
                                        <th>CLASS</th>
                                        <th>SUB</th>
                                        <th>STOCK TYPE</th>
                                        <th>UNIT</th>
                                        <th class="text-right">RATE</th>
                                        <th class="text-right">COST</th>
                                        <th class="text-center">INACTIVE</th>
                                        <th class="text-center col-1">
                                            <a href="{{ route('maintenanceinventoryitem_create') }}"
                                                class="text-white btn-sm"> <i class="fas fa-plus"></i></a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($items as $list)
                                        <tr>
                                            <td> {{ $list->CODE }}</td>
                                            <td> {{ $list->DESCRIPTION }}</td>
                                            <td> {{ $list->ITEM_TYPE }} </td>
                                            <td class="text-center"> {{ $list->TAXABLE ? 'Yes' : 'No' }} </td>
                                            <td> {{ $list->GROUP_NAME }}</td>
                                            <td> {{ $list->CLASS }}</td>
                                            <td> {{ $list->SUB_CLASS }}</td>
                                            <td> {{ $list->STOCK_TYPE }}</td>
                                            <td> {{ $list->UNIT_BASE }}</td>
                                            <td class="text-right">
                                                {{ $list->RATE ? number_format($list->RATE, 2) : '' }}</td>
                                            <td class="text-right">
                                                {{ $list->COST ? number_format($list->COST, 2) : '' }}</td>
                                            <td class="text-center"> {{ $list->INACTIVE ? 'Yes' : 'No' }}</td>
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
