    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Quick Create</div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="form">


                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                </div>
                                <div class="col-6 text-right">
                                    <div wire:loading.delay>
                                        <span class="spinner"></span>
                                    </div>
                                    <button class="btn btn-success btn-sm" wire:click='change()'
                                        wire:loading.attr='disabled'>Update</button>
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm"
                                        wire:loading.attr='disabled'>
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
