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
                                                <input type="text" wire:model.live.debounce.120ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='LOCATION_ID'
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
                                        <th>

                                            <span type='button' wire:click="sorting('c.DESCRIPTION')">Category</span>
                                            @if ($sortby == 'c.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif

                                        </th>
                                        <th>
                                            <span type='button' wire:click="sorting('s.DESCRIPTION')">Sub
                                                Category</span>
                                            @if ($sortby == 's.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="bg-primary">
                                            <span type='button' wire:click="sorting('item.CODE')">Code</span>
                                            @if ($sortby == 'item.CODE')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="bg-primary">
                                            <span type='button' wire:click="sorting('item.DESCRIPTION')">Item
                                                Description</span>
                                            @if ($sortby == 'item.DESCRIPTION')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-center bg-info">
                                            <span type='button' wire:click="sorting('u.SYMBOL')">Unit</span>
                                            @if ($sortby == 'u.SYMBOL')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="text-center bg-warning">
                                            <span type='button' wire:click="sorting('QTY_ON_HAND')">Quantity</span>
                                            @if ($sortby == 'QTY_ON_HAND')
                                                @if ($isDesc)
                                                    <i class="fa fa-caret-up" aria-hidden="true"></i>
                                                @else
                                                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                                                @endif
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->CLASS_NAME }}

                                            </td>
                                            <td> {{ $list->SUB_NAME }}</td>
                                            <td> {{ $list->CODE }}</td>
                                            <td> {{ $list->DESCRIPTION }}</td>
                                            <td class="text-center"> {{ $list->SYMBOL }} </td>
                                            <td class="text-center">
                                                <span type="button"
                                                    class="text-sm @if ($list->QTY_ON_HAND < 0) text-danger @elseif ($list->QTY_ON_HAND == 0) text-info  @else text-primary @endif"
                                                    wire:click='OnClick({{ $list->ID }})'>{{ number_format($list->QTY_ON_HAND ?? 0, 2) }}</span>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @livewire('List.InventoryDetailsModal')
            </div>
        </div>
    </section>




</div>
