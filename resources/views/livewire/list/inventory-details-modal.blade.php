<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">{{ $ITEM_NAME }} : Inventory Item</div>
                    <div class="modal-body">
                        <div class="table-responsive" id="tableContainer" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class='bg-sky text-xs'>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Contact</th>
                                        <th class="text-right">Quantity</th>
                                        <th class="text-right">Ending</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->TYPE }}</td>
                                            <td>{{ date('m/d/Y', strtotime($list->SOURCE_REF_DATE)) }}</td>
                                            <td>{{ $list->TX_CODE }}</td>
                                            <td>{{ $list->CONTACT_NAME }}</td>
                                            <td class="text-right">{{ number_format($list->QUANTITY, 1) }}</td>
                                            <td class="text-right">{{ number_format($list->ENDING_QUANTITY, 1) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="button" wire:click='scrollDown()' class="btn btn-info btn-sm"><i class="fa fa-angle-double-down" aria-hidden="true"></i> Last Row</button>
                        <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
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
