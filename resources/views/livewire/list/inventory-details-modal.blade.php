<div>
    {{-- <a href="#" wire:click="openModal()" class="text-xs">
        {{ number_format($QUANTITY, 0) }}
    </a> --}}
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">{{ $ITEM_NAME }} : Inventory Item</div>
                    <div class="modal-body">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class='bg-sky text-xs'>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Ref</th>
                                    <th>Quantity</th>
                                    <th>Ending</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->SOURCE_REF_DATE }}</td>
                                        <td>{{ $list->TYPE }}</td>
                                        <td></td>
                                        <td> {{ number_format($list->QUANTITY, 1) }}</td>
                                        <td> {{ number_format($list->ENDING_QUANTITY, 1) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" wire:click='create()' class="btn btn-success btn-sm">Create</button> --}}
                        <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
