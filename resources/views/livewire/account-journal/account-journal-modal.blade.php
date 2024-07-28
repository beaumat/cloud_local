<div>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Account Journal No. {{ $JOURNAL_NO }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm  table-bordered">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <td class="col-2">Code</td>
                                    <td class="col-6">Account Title</td>
                                    <th class="col-2 text-right">Debit</th>
                                    <th class="col-2 text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">

                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->ACCOUNT_CODE }}</td>
                                        <td>{{ $list->ACCOUNT_TITLE }}</td>
                                        <td class="text-right">
                                            {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}</td>
                                        <td class="text-right">
                                            {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
