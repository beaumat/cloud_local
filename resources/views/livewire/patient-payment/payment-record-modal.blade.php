<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document" style="max-width: 100%; height: 100%; margin: 0;">
                <div class="modal-content" style="height: 100%; display: flex; flex-direction: column;">
                    <div class="modal-header">
                        <h6 class="modal-title">Assistance Record</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="flex: 1; overflow-y: auto;">
                        @livewire('Patient.AssistanceRecord', ['CONTACT_ID' => $CONTACT_ID])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
