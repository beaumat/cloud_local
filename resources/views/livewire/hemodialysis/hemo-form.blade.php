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
                                    <a class="text-white" href="{{ route('transactionshemo') }}"> Hemodialysis Treatment
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
                                            @if ($Modify)
                                                <livewire:select-option name="CUSTOMER_ID" titleName="Patient"
                                                    :options="$patientList" :zero="true" :isDisabled=false
                                                    wire:model='CUSTOMER_ID' />
                                            @else
                                                <livewire:select-option name="CUSTOMER_ID" titleName="Patient"
                                                    :options="$patientList" :zero="true" :isDisabled=true
                                                    wire:model='CUSTOMER_ID' />
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif

                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info"
                                                    @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0 && $STATUS > 0)
                                            <a id="new" title="Create"
                                                href="{{ route('transactionsservice_charges_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
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
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav text-sm nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == '1st') active @endif"
                                        id="custom-tabs-four-1st-tab" wire:click="SelectTab('1st')" data-toggle="pill"
                                        href="#custom-tabs-four-1st" role="tab" aria-controls="custom-tabs-four-1st"
                                        aria-selected="true">Treatment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == '2nd') active @endif"
                                        id="custom-tabs-four-2nd-tab" wire:click="SelectTab('2nd')" data-toggle="pill"
                                        href="#custom-tabs-four-2nd" role="tab" aria-controls="custom-tabs-four-2nd"
                                        aria-selected="true">Access</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == '3rd') active @endif"
                                        id="custom-tabs-four-3rd-tab" wire:click="SelectTab('3rd')" data-toggle="pill"
                                        href="#custom-tabs-four-3rd" role="tab"
                                        aria-controls="custom-tabs-four-3rd" aria-selected="true">Assessment</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link @if ($tab == '4th') active @endif"
                                        id="custom-tabs-four-4th-tab" wire:click="SelectTab('4th')"
                                        data-toggle="pill" href="#custom-tabs-four-4th" role="tab"
                                        aria-controls="custom-tabs-four-4th" aria-selected="true">Nurses Notes</a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade @if ($tab == '1st') show active @endif "
                                    id="custom-tabs-four-1st" role="tabpanel">
                                    <div class="row"
                                        @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('Hemodialysis.Treatment', ['ID' => $ID])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade @if ($tab == '2nd') show active @endif "
                                    id="custom-tabs-four-2nd" role="tabpanel">
                                    <div class="row"
                                        @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('Hemodialysis.Access', ['ID' => $ID])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade @if ($tab == '3rd') show active @endif "
                                    id="custom-tabs-four-3rd" role="tabpanel">
                                    <div class="row"
                                        @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('Hemodialysis.Assessment', ['ID' => $ID])
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade @if ($tab == '4th') show active @endif "
                                    id="custom-tabs-four-4th" role="tabpanel">
                                    <div class="row"
                                        @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('Hemodialysis.NursesNotes', ['ID' => $ID])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-sm btn-primary" wire:click='update_all'>Save</button>
                </div>
            </div>
        </div>
    </section>

</div>
