<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('bankingbank_recon') }}"> Bank Reconciliation
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }}</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    @if ($Modify && $ID == 0)
                                                        <livewire:select-option name="ACCOUNT_ID1"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled="false" wire:model.live='ACCOUNT_ID' />
                                                    @else
                                                        <livewire:select-option name="ACCOUNT_ID2"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled="true" wire:model.live='ACCOUNT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3 col-3">
                                                    <livewire:number-input name="BEGINNING_BALANCE"
                                                        titleName="Beginning Balance" isDisabled="{{ true }}"
                                                        wire:model='BEGINNING_BALANCE' />
                                                </div>
                                                <div class="col-md-3 col-3">
                                                    <livewire:number-input name="ENDING_BALANCE"
                                                        titleName="Ending Balance" isDisabled="{{ !$Modify }}"
                                                        wire:model='ENDING_BALANCE' />
                                                </div>

                                                <div class="col-md-3 col-3">
                                                    <livewire:number-input name="SC_RATE"
                                                        titleName="Service Charges Rate"
                                                        isDisabled="{{ !$Modify }}" wire:model='SC_RATE' />
                                                </div>
                                                <div class="col-md-3 col-3">
                                                    <livewire:date-input name="SC_DATE" titleName="Date"
                                                        wire:model.live='SC_DATE' isDisabled="{{ !$Modify }}" />
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    @if ($Modify)
                                                        <livewire:select-option name="SC_ACCOUNT_ID1"
                                                            titleName="Account" :options="$sc_accountList" :zero="true"
                                                            :isDisabled="false" wire:model='SC_ACCOUNT_ID' />
                                                    @else
                                                        <livewire:select-option name="SC_ACCOUNT_ID2"
                                                            titleName="Account" :options="$sc_accountList" :zero="true"
                                                            :isDisabled="true" wire:model='SC_ACCOUNT_ID' />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($ID == 0 && auth()->user()->date_enabled)
                                                        <livewire:date-input name="DATE"
                                                            titleName="Bank Statement Date" wire:model.live='DATE'
                                                            :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE"
                                                            titleName="Bank Statement Date" wire:model.live='DATE'
                                                            :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        isDisabled="{{ !$Modify }}" wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify }}" wire:model='LOCATION_ID' />
                                                </div>

                                                <div class="col-md-12">
                                

                                                    <div class="row">
                                                        <div class="col-md-3 col-3">
                                                            <livewire:number-input name="IE_RATE"
                                                                titleName="Interest Earn "
                                                                isDisabled="{{ !$Modify }}" wire:model='IE_RATE' />
                                                        </div>
                                                        <div class="col-md-3 col-3">
                                                            <livewire:date-input name="IE_DATE" titleName="Date"
                                                                wire:model.live='IE_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            @if ($Modify)
                                                                <livewire:select-option name="IE_ACCOUNT_ID1"
                                                                    titleName="Account" :options="$ie_accountList"
                                                                    :zero="true" :isDisabled="false"
                                                                    wire:model='IE_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option name="EI_ACCOUNT_ID2"
                                                                    titleName="Account" :options="$ie_accountList"
                                                                    :zero="true" :isDisabled="true"
                                                                    wire:model='IE_ACCOUNT_ID' />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == $openStatus || $STATUS == 16)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>
                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel()'
                                                        name="cancelbtn" wire:confirm='Want to cancel?'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                                <button type="button" wire:click='getPosted()'
                                                    class="btn btn-sm btn-warning"
                                                    wire:confirm='Are you sure you want to post?'>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                            @endif
                                        @endif
                                        @if ($STATUS == 15)
                                            @can('banking.bank-recon.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-md-6 col-6">
                                        @if ($STATUS > 0)
                                            @if ($STATUS != 16 && $ID > 0)
                                                @can('banking.bank-recon.print')
                                                    <a type="button" target="_BLANK"
                                                        href="{{ route('vendorsbills_print', ['id' => $ID]) }}"
                                                        class="btn btn-sm btn-dark">
                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                    </a>
                                                    {{-- <button type="button" wire:click='OpenJournal()'
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                    </button> --}}
                                                @endcan
                                            @endif
                                            @can('banking.bank-recon.create')
                                                <a id="new" title="Create"
                                                    href="{{ route('bankingbank_recon_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endcan
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
    @if ($ID > 0)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                @livewire('BankRecon.BankReconFormItems', ['ACCOUNT_RECONCILIATION_ID' => $ID, 'STATUS' => $STATUS])
                            </div>
                            <div class="col-3">
                                @if ($STATUS == 0 || $STATUS == 16)
                                    <div class="form-group">
                                        <button wire:click='openSalesCollection()' class="btn btn-sm btn-success">
                                            Collection & Deposit
                                        </button>
                                        <button wire:click='openCheckPayment()' class="btn btn-sm btn-primary">
                                            Check Payment
                                        </button>
                                    </div>
                                @endif
                                @livewire('BankRecon.BankReconDetails', ['ACCOUNT_RECONCILIATION_ID' => $ID])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @livewire('BankRecon.CollectionDeposit', ['ACCOUNT_RECONCILIATION_ID' => $ID, 'ACCOUNT_ID' => $ACCOUNT_ID])
        @livewire('BankRecon.CheckPayment', ['ACCOUNT_RECONCILIATION_ID' => $ID, 'ACCOUNT_ID' => $ACCOUNT_ID])
    @endif

</div>
