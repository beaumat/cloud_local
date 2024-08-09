<div>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Account Receivable Form</div>
                    <div class="modal-body">
                        <div class="form-group row mb-2">

                            <div class="col-md-6">
                                <livewire:date-input name="DATE" titleName="Date Created" wire:model='DATE'
                                    :isDisabled=true />
                            </div>

                            <div class="col-md-6">
                                <livewire:text-input name="CODE" titleName="SOA No." :isDisabled=true
                                    wire:model='CODE' />
                            </div>
                        </div>
                        <div class="form-group row">

                            <div class="col-md-6">
                                <livewire:date-input name="AR_DATE" titleName="AR Date" wire:model='AR_DATE'
                                    :isDisabled="false" />
                            </div>

                            <div class="col-md-6">
                                <livewire:text-input name="AR_NO" titleName="AR No." :isDisabled=false
                                    wire:model='AR_NO' />
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" wire:click='save()' class="btn btn-success btn-sm">
                                        Save
                                    </button>
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">
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
</div>
