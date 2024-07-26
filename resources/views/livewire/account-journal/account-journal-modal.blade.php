<div>
    <button wire:click="openModal" class="btn btn-primary btn-sm text-xs ">
        Journal
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
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
                                    <th class="col-1">JNL</th>
                                    <th class="col-1">Date</th>
                                    <th class="col-1">Type</th>
                                    <th class="col-1">Reference</th>
                                    <th class="col-2">Name</th>
                                    <th class="col-1">Location</th>
                                    <th class="col-1">Account</th>
                                    <th class="col-2">Particular.</th>
                                    <th class="col-1 text-right">Debit</th>
                                    <th class="col-1 text-right">Credit</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                 <tr>
                                    <td class="col-1">JNL</td>
                                    <td class="col-1">Date</td>
                                    <td class="col-1">Type</td>
                                    <td class="col-1">Reference</td>
                                    <td class="col-2">Name</td>
                                    <td class="col-1">Location</td>
                                    <td class="col-1">Account</td>
                                    <td class="col-2">Particular.</td>
                                    <td class="col-1 text-right">Debit</td>
                                    <td class="col-1 text-right">Credit</td>
                                </tr>
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
