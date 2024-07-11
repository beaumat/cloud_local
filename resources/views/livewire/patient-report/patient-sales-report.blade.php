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
                                    <a class="text-white" href="{{ route('reportspatient_sales_report') }}"> Patient
                                        Sales Report </a>
                                </div>
                                <div class="col-sm-6 text-right">

                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <livewire:select-option name="PATIENT_ID" titleName="Patient"
                                                :options="$patientList" :zero="true" :isDisabled=false
                                                wire:model='PATIENT_ID' />
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE_FROM" titleName="Date From"
                                                        wire:model.live='DATE_FROM' :isDisabled="false" />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE_TO" titleName="Date To"
                                                        wire:model.live='DATE_TO' :isDisabled="false" />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false" :isDisabled=false
                                                        wire:model.iive='LOCATION_ID' />
                                                </div>
                                            </div>
                                        </div>
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

                        </div>
                        <div class="card-body">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
