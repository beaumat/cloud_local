<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Payment List</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm ">
                            <thead class="text-xs bg-success">
                                <tr>
                                    <th class="col-1 text-center"></th>
                                    <th class="col-1">Date</th>
                                    <th class="col-1">Type</th>
                                    <th class="col-1">Ref No.</th>
                                    <th class="col-1">Payment Method</th>
                                    <th class="col-6">Received From</th>

                                    <th class="col-1 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">

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
