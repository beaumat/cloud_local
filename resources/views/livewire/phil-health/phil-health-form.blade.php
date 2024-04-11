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
                                    <a class="text-white" href="{{ route('transactionsphic') }}">
                                        Philhealth
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
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    @if ($Modify)
                                                        <livewire:select-option name="CONTACT_ID" titleName="Patient"
                                                            :options="$patientList" :zero="true" :isDisabled=false
                                                            wire:model='CONTACT_ID' />
                                                    @else
                                                        <livewire:select-option name="CONTACT_ID" titleName="Patient"
                                                            :options="$patientList" :zero="true" :isDisabled=true
                                                            wire:model='CONTACT_ID' />
                                                    @endif

                                                </div>
                                                <div class="col-12">
                                                    @if ($Modify)
                                                        <livewire:text-input name="FINAL_DIAGNOSIS"
                                                            titleName="Final Diagnosis" :isDisabled=false
                                                            wire:model='FINAL_DIAGNOSIS' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="FINAL_DIAGNOSIS"
                                                            titleName="Final Diagnosis" :isDisabled=true
                                                            wire:model='FINAL_DIAGNOSIS' :vertical="false" />
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    @if ($Modify)
                                                        <livewire:text-input name="OTHER_DIAGNOSIS"
                                                            titleName="Other Diagnosis" :isDisabled=false
                                                            wire:model='OTHER_DIAGNOSIS' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="OTHER_DIAGNOSIS"
                                                            titleName="Other Diagnosis" :isDisabled=true
                                                            wire:model='OTHER_DIAGNOSIS' :vertical="false" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif

                                                </div>
                                                <div class="col-md-3">
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
                                                <div class="col-md-3">

                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DATE_ADMITTED" titleName="Date Admitted"
                                                        wire:model='DATE_ADMITTED' :isDisabled="false" />
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:time-input name="TIME_ADMITTED" titleName="Time Admitted"
                                                        wire:model='TIME_ADMITTED' :isDisabled="false" />
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DATE_DISCHARGED"
                                                        titleName="Date Discharged" wire:model='DATE_DISCHARGED'
                                                        :isDisabled="false" />
                                                </div>


                                                <div class="col-md-3">
                                                    <livewire:time-input name="TIME_DISCHARGED"
                                                        titleName="Time Discharged" wire:model='TIME_DISCHARGED'
                                                        :isDisabled="false" />
                                                </div>
                                                <div class="col-6">
                                                    @if ($Modify)
                                                        <livewire:text-input name="FIRST_CASE_RATE"
                                                            titleName="First Case Rate" :isDisabled=false
                                                            wire:model='FIRST_CASE_RATE' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="FIRST_CASE_RATE"
                                                            titleName="First Case Rate" :isDisabled=true
                                                            wire:model='FIRST_CASE_RATE' :vertical="false" />
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    @if ($Modify)
                                                        <livewire:text-input name="SECOND_CASE_RATE"
                                                            titleName="Second Case Rate" :isDisabled=false
                                                            wire:model='SECOND_CASE_RATE' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="SECOND_CASE_RATE"
                                                            titleName="Second Case Rate" :isDisabled=true
                                                            wire:model='SECOND_CASE_RATE' :vertical="false" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
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
                                                href="{{ route('transactionsphic_create') }}"
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
    @if ($ID > 0)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'soa') active @endif"
                                            id="custom-tabs-four-soa-tab" wire:click="SelectTab('soa')"
                                            data-toggle="pill" href="#custom-tabs-four-soa" role="tab"
                                            aria-controls="custom-tabs-four-soa" aria-selected="true">
                                            Statement of Accounts
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'treatment') active @endif"
                                            id="custom-tabs-four-treatment-tab" wire:click="SelectTab('treatment')"
                                            data-toggle="pill" href="#custom-tabs-four-treatment" role="tab"
                                            aria-controls="custom-tabs-four-treatment" aria-selected="true">Treatment
                                            Summary</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'csf') active @endif"
                                            id="custom-tabs-four-csf-tab" wire:click="SelectTab('csf')"
                                            data-toggle="pill" href="#custom-tabs-four-csf" role="tab"
                                            aria-controls="custom-tabs-four-csf" aria-selected="true">CSF Form</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'cf1') active @endif"
                                            id="custom-tabs-four-cf1-tab" wire:click="SelectTab('cf1')"
                                            data-toggle="pill" href="#custom-tabs-four-cf1" role="tab"
                                            aria-controls="custom-tabs-four-cf1" aria-selected="true">CF-1 Form</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'soa') show active @endif"
                                        id="custom-tabs-four-soa" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-soa-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">
                                                @livewire('PhilHealth.StatementOfAccount', ['ID' => $ID])
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if ($tab == 'treatment') show active @endif"
                                        id="custom-tabs-four-treatment" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-treatment-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>

                                                @livewire('PhilHealth.TreatmentSummary')
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'csf') show active @endif"
                                        id="custom-tabs-four-csf" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-csf-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">
                                                @livewire('PhilHealth.CsfForm')
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if ($tab == 'cf1') show active @endif"
                                        id="custom-tabs-four-cf1" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-cf1-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">

                                                @livewire('PhilHealth.Cf1Form')
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 text-left">

                                    </div>
                                    <div class="col-md-8">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
