<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">

                                    <a class="text-white" href="{{ route('patientspayment_period') }}"> Payment Period
                                        (ACPN)
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">

                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save()'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:number-input name="TOTAL_PAYMENT"
                                                            titleName="Gross Total" :isDisabled=false
                                                            wire:model='TOTAL_PAYMENT' />
                                                    @else
                                                        <livewire:number-input name="TOTAL_PAYMENT"
                                                            titleName="Gross Total" :isDisabled=true
                                                            wire:model='TOTAL_PAYMENT' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:dropdown-option name="BANK_ACCOUNT_ID"
                                                            :isDisabled=false titleName="Bank Account" :options="$accountList"
                                                            :zero="true" wire:model.live='BANK_ACCOUNT_ID' />
                                                    @else
                                                        <livewire:dropdown-option name="BANK_ACCOUNT_ID"
                                                            :isDisabled=true titleName="Bank Account" :options="$accountList"
                                                            :zero="true" wire:model.live='BANK_ACCOUNT_ID' />
                                                    @endif
                                                </div>

                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE_FROM1" titleName="Date From"
                                                            wire:model='DATE_FROM' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE_FROM2" titleName="Date From"
                                                            wire:model='DATE_FROM' :isDisabled="true" />
                                                    @endif
                                                </div>

                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE_TO1" titleName="Date To"
                                                            wire:model='DATE_TO' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE_TO2" titleName="Date To"
                                                            wire:model='DATE_TO' :isDisabled="true" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE" titleName="O.R Date"
                                                            wire:model='DATE' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE" titleName="O.R Date"
                                                            wire:model='DATE' :isDisabled="true" />
                                                    @endif

                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="OR No."
                                                            :isDisabled=false wire:model='RECEIPT_NO' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="OR No."
                                                            :isDisabled=true wire:model='RECEIPT_NO' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify && $TOTAL_PAYMENT == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">

                                        @if ($Modify)
                                            <button type="submit" class="btn btn-sm btn-primary"> <i
                                                    class="fa fa-floppy-o" aria-hidden="true"></i>
                                                {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                            @if ($ID > 0)
                                                <button type="button" wire:click='updateCancel'
                                                    wire:confirm='Want to cancel?' class="btn btn-sm btn-danger"><i
                                                        class="fa fa-ban" aria-hidden="true"></i> Cancel</button>
                                            @endif
                                        @else
                                            <button type="button" wire:click='getModify()' class="btn btn-sm btn-info">
                                                <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                            </button>

                                        @endif


                                    </div>

                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
