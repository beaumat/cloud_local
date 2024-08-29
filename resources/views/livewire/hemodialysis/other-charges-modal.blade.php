<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document"
                style="margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary">Add</h6>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <form wire:submit.prevent="AddCharge" wire:loading.attr='disabled'>
                        <div class="modal-body">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                            <div class="row">
                                <div class="col-md-4 text-right text-sm text-primary">
                                    Item
                                </div>
                                <div class="col-md-8">
                                    <label class="text-xs">{{ $ITEM_NAME }}</label>
                                </div>
                                <div class="col-md-4 text-right text-sm text-primary">
                                    Quantity
                                </div>
                                <div class="col-md-8">
                                    <input type="number" class="form-control form-control-sm" wire:model='QUANTITY' />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div wire:loading.delay>
                                <span class="spinner"></span>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm"
                                wire:loading.attr='hidden'>Add</button>
                            <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm"
                                wire:loading.attr='hidden'>Close</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
