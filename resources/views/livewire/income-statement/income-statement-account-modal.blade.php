<div>

    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Account Transaction Details</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($showModal)
                            @livewire('IncomeStatement.IncomeStatementAccountDetails', ['id' => $ACCOUNT_ID, 'year' => $YEAR, 'month' => $MONTH, 'locationid' => $LOCATION_ID])
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-xs btn-secondary" wire:click='closeModal()'> Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
