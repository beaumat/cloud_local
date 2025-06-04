<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('maintenanceothersitem-active-list') }}">Item Inventory  </a> | {{ $ITEM_NAME }} 
                    </h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <button type="button" wire:click='exportData()' wire:loading.attr='disabled'
                                class="btn btn-success btn-sm">
                                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export
                            </button>
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
                        

                            <div id="tableContainer" style="max-height:70vh; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky sticky-header">
                                        <tr>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Ref No.</th>
                                            <th class="col-2">Name/Details</th>
                                            <th class="col-4">Notes</th>
                                            <th class="text-right">Qty</th>
                                            <th class="text-right">Ending Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($dataList as $list)
                                            <tr>
                                                <td>{{ $list->TYPE }}</td>
                                                <td wire:confirm='Are you sure'
                                                    wire:click="refreshOnHand('{{ $list->SOURCE_REF_DATE }}')">
                                                    {{ date('m/d/Y', strtotime($list->SOURCE_REF_DATE)) }}</td>
                                                <td>{{ $list->TX_CODE }}</td>
                                                <td>{{ $list->CONTACT_NAME }}</td>
                                                <td>{{ $list->TX_NOTES }}</td>
                                                <td
                                                    class="text-right @if ($list->QUANTITY > 0) text-success @else text-danger @endif">
                                                    @if ($list->QUANTITY > 0)
                                                        +
                                                    @endif
                                                    {{ number_format($list->QUANTITY, 1) }}
                                                </td>
                                                <td class="text-right text-primary font-weight-bold ">
                                                    {{ number_format($list->ENDING_QUANTITY, 1) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>
                        </div>
                        {{-- <div class="card-footer">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                 

                                        <div wire:loading.delay>
                                            <span class='spinner'></span>
                                        </div>
                                    </div>
                                    <div class='col-md-6 text-right'>
                                        <button type="button" wire:click='scrollDown()' class="btn btn-info btn-sm"><i
                                                class="fa fa-angle-double-down" aria-hidden="true"></i>
                                            Last Row</button>
                                        <button type="button" wire:click='closeModal()'
                                            class="btn btn-danger btn-sm">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

            </div>
        </div>
    </section>

    @script
        <script>
            $wire.on('scrollToBottom', (eventData) => {
                const tableContainer = document.getElementById('tableContainer');
                if (tableContainer) {
                    tableContainer.scrollTop = tableContainer.scrollHeight;
                }
            });
        </script>
    @endscript

</div>
