<div>
    <div class="container-fluid">
        <div class="row">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
            <div class="col-md-12">
                <div class="card">
                    <form id="quickForm" wire:submit.prevent='save'>
                        <div class="card-body bg-light">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <livewire:text-input name="Code" titleName="Reference No." :isDisabled=false
                                            wire:model='CODE' />
                                    </div>
                                    <div class="col-md-8">
                                        <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=false
                                            wire:model='NOTES' :vertical="false" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <livewire:number-input name="AMOUNT" titleName="Amount Deposit"
                                            :isDisabled=false wire:model.live.lazy='AMOUNT' />
                                    </div>
                                    <div class="col-md-4">
                                        <livewire:dropdown-option name="PAYMENT_METHOD_ID" :isDisabled=false
                                            titleName="Payment Method" :options="$paymentMethodList" :zero="false"
                                            wire:model.live='PAYMENT_METHOD_ID' />
                                    </div>
                                    <div class="col-md-4">
                                        <livewire:number-input name="AMOUNT_APPLIED" titleName="Amount Applied"
                                            :isDisabled=false wire:model='AMOUNT_APPLIED' />
                                    </div>
                                </div>
                                <div class="row">
                                    @if ($showCardNo)
                                        <div class="col-md-6">
                                            <livewire:text-input name="CARD_NO" titleName="Card No." :isDisabled=false
                                                wire:model='CARD_NO' />
                                        </div>
                                    @endif

                                    @if ($showCardDateExpire)
                                        <div class="col-md-6">
                                            <livewire:date-input name="CARD_EXPIRY_DATE" titleName="Card Expired"
                                                wire:model='CARD_EXPIRY_DATE' :isDisabled="false" />
                                        </div>
                                    @endif

                                    @if ($showReceiptNo)
                                        <div class="col-md-6">
                                            <livewire:text-input name="RECEIPT_REF_NO" titleName="Receipt No."
                                                :isDisabled=false wire:model='RECEIPT_REF_NO' />

                                        </div>
                                    @endif

                                    @if ($showReceiptDate)
                                        <div class="col-md-6">
                                            <livewire:date-input name="RECEIPT_DATE" titleName="Receipt Date"
                                                wire:model='RECEIPT_DATE' :isDisabled="false" />
                                        </div>
                                    @endif

                                    @if ($showFileName)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="fileUpload" class="text-xs">PDF document file
                                                    @if ($PDF)
                                                        <i class="fa fa-check-circle text-success"
                                                            aria-hidden="true"></i>
                                                    @endif
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <div class="custom-file text-xs">
                                                        <input type="file" class="custom-file-input text-xs"
                                                            id="fileUpload" wire:model='PDF'>
                                                        <label class="custom-file-label text-xs" for="fileUpload">
                                                            @if ($PDF)
                                                                {{ $PDF->getClientOriginalName() }}
                                                            @else
                                                                Choose file
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <button type="submit" class="btn btn-sm btn-info">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Add & Save
                                    </button>
                                </div>
                                <div class="text-right col-6 col-md-6">
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
