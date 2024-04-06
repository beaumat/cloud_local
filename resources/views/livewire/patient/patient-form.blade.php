<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactpatients') }}"> Patients Profile</a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:text-input name="NAME" titleName="Patients Name"
                                                wire:model='NAME' :isDisabled='true' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Profile No."
                                                wire:model='ACCOUNT_NO' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:date-input name="DATE_ADMISSION" titleName="Date Admission"
                                                wire:model.live='DATE_ADMISSION' />
                                        </div>
                                        <div class="col-md-2">
                                            <div class="row">
                                                <div class="col-md-12"><br /></div>
                                                <div class="col-md-12 text-right">
                                                    <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                        wire:model='INACTIVE' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card  card-tabs mt-2">
                                    <div class="card-header p-0 pt-1 border-bottom-0">
                                        <ul class="nav nav-tabs text-sm p-1" id="custom-content-below-tab"
                                            role="tablist">
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('gen')"
                                                    class="nav-link @if ($selectTab == 'gen') active @endif"
                                                    id="custom-content-below-general-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-general-info" role="tab"
                                                    aria-controls="custom-content-below-general-info"
                                                    aria-selected="true">General Info</a>
                                            </li>

                                            <li class="nav-item">
                                                <a wire:click="SelectTab('add')"
                                                    class="nav-link @if ($selectTab == 'add') active @endif"
                                                    id="custom-content-below-add-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-add-info" role="tab"
                                                    aria-controls="custom-content-below-add-info" aria-selected="false">
                                                    Addional Info
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('tax')"
                                                    class="nav-link @if ($selectTab == 'tax') active @endif"
                                                    id="custom-content-below-tax-info-tab" data-toggle="pill"
                                                    href="#custom-content-below-tax-info" role="tab"
                                                    aria-controls="custom-content-below-tax-info" aria-selected="false">
                                                    Schedule
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="tab-content text-sm" id="custom-content-below-tabContent">
                                            <div class="tab-pane fade @if ($selectTab == 'gen') show active @endif"
                                                id="custom-content-below-general-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-general-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <livewire:text-input name="FIRST_NAME"
                                                                titleName="First Name" wire:model.live='FIRST_NAME' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="LAST_NAME" titleName="Last Name"
                                                                wire:model.live='LAST_NAME' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:text-input name="MIDDLE_NAME"
                                                                titleName="Middle Name" wire:model.live='MIDDLE_NAME' />
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="row">
                                                                                <div class="col-md-2">
                                                                                    <div class="form-group mt-2">
                                                                                        <label>Gender</label>
                                                                                        <div class="form-check">
                                                                                            <label
                                                                                                class="form-check-label">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    wire:model="GENDER"
                                                                                                    value="0" />
                                                                                                Male
                                                                                            </label>
                                                                                        </div>

                                                                                        <div class="form-check">

                                                                                            <label
                                                                                                class="form-check-label">
                                                                                                <input
                                                                                                    class="form-check-input"
                                                                                                    type="radio"
                                                                                                    wire:model="GENDER"
                                                                                                    value="1" />
                                                                                                Female
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <livewire:date-input
                                                                                                name="DATE_OF_BIRTH"
                                                                                                titleName="Date of Birth"
                                                                                                wire:model.live='DATE_OF_BIRTH' />
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div
                                                                                                class="form-group mt-2">
                                                                                                <label
                                                                                                    class=" col-form-label ">Age</label>
                                                                                                <div class="row">
                                                                                                    <label
                                                                                                        class="col-12 form-check-label">{{ $age }}</label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <livewire:date-input
                                                                                        name="HIRE_DATE"
                                                                                        titleName="Date of Death"
                                                                                        wire:model.live='HIRE_DATE' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <div class="mt-2">
                                                                                <label for="postal-address"
                                                                                    class="text-xs">Postal
                                                                                    Address</label>
                                                                                <textarea type="text" autocomplete="off" wire:model='POSTAL_ADDRESS' class="text-xs form-control form-control-sm"
                                                                                    id="pos_tal_address" rows="2">
                                                                                </textarea>
                                                                            </div>

                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <livewire:text-input name="CUSTOM_FIELD1"
                                                                                titleName="Diagnosis"
                                                                                wire:model='CUSTOM_FIELD1' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <livewire:text-input name="TAXPAYER_ID"
                                                                                titleName="PhilHealth ID Number"
                                                                                wire:model='TAXPAYER_ID' />

                                                                        </div>
                                                                        <div class="col-md-6">

                                                                            <livewire:text-input name="CONTACT_PERSON"
                                                                                titleName="Contact Person"
                                                                                wire:model='CONTACT_PERSON' />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <livewire:text-input name="TELEPHONE_NO"
                                                                                titleName="Telephone Number"
                                                                                wire:model='TELEPHONE_NO' />
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <livewire:text-input name="MOBILE_NO"
                                                                                titleName="Mobile Number"
                                                                                wire:model='MOBILE_NO' />

                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <livewire:text-input name="EMAIL"
                                                                                titleName="Email"
                                                                                wire:model='EMAIL' />
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <livewire:select-option name="LOCATION_ID"
                                                                                :options="$locationList" :zero="true"
                                                                                titleName="Location"
                                                                                wire:model='LOCATION_ID' />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tab-pane fade @if ($selectTab == 'add') show active @endif"
                                                id="custom-content-below-add-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-add-info-tab">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        {{-- <div class="col-md-4">
                                                            <livewire:select-option name="TAX_ID" :options="$taxList"
                                                                :zero="true" titleName="Output Tax"
                                                                wire:model='TAX_ID' :key="$taxList->pluck('ID')->join('_')" />

                                                        </div> --}}

                                                        <div class="col-md-4">
                                                            <livewire:select-option name="GROUP_ID" :options="$contactGroup"
                                                                :zero="true" titleName="Group"
                                                                wire:model='GROUP_ID' />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="SALES_REP_ID"
                                                                :options="$salesMan" :zero="true" titleName="Doctor"
                                                                wire:model='SALES_REP_ID' :key="$salesMan->pluck('ID')->join('_')" />
                                                        </div>


                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PAYMENT_TERMS_ID"
                                                                :options="$paymentTermList" :zero="true"
                                                                titleName="Payment Terms"
                                                                wire:model='PAYMENT_TERMS_ID' :key="$paymentTermList->pluck('ID')->join('_')" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <livewire:number-input name="CREDIT_LIMIT"
                                                                titleName="Credit Limit" wire:model='CREDIT_LIMIT' />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PREF_PAYMENT_METHOD_ID"
                                                                :options="$paymentMethod" :zero="true"
                                                                titleName="Payment Method"
                                                                wire:model='PREF_PAYMENT_METHOD_ID'
                                                                :key="$paymentMethod->pluck('ID')->join('_')" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <livewire:text-input name="CREDIT_CARD_NO"
                                                                titleName="Credit Card No"
                                                                wire:model='CREDIT_CARD_NO' />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="mt-2">
                                                                <label for="ExpiryDate" class="text-sm">Expiry Date
                                                                </label>
                                                                <input type="date" name="ExpiryDate"
                                                                    class="form-control form-control-sm"
                                                                    wire:model='CREDIT_CARD_EXPIRY_DATE' />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <livewire:select-option name="PRICE_LEVEL_ID"
                                                                :options="$priceLevels" :zero="true"
                                                                titleName="Price Level" wire:model='PRICE_LEVEL_ID' />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($selectTab == 'tax') show active @endif"
                                                id="custom-content-below-tax-info" role="tabpanel"
                                                aria-labelledby="custom-content-below-tax-info-tab">
                                                <div class="container-fluid">
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <livewire:select-option name="PATIENT_TYPE_ID"
                                                                :options="$patientTypeList" :zero="true"
                                                                titleName="Patient Type"
                                                                wire:model.live='PATIENT_TYPE_ID' vertical="true" />
                                                        </div>
                                                        <div class="col-md-6"></div>
                                                        <div class="col-md-6">
                                                            <livewire:select-option name="PATIENT_STATUS_ID"
                                                                :options="$patientStatusList" :zero="true"
                                                                titleName="Status" wire:model.live='PATIENT_STATUS_ID'
                                                                vertical="true" />
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <livewire:checkbox-input name="LONG_HRS_DURATION"
                                                                        titleName="5 HRS/ 6HRS DURATION"
                                                                        wire:model.live='LONG_HRS_DURATION' />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <livewire:checkbox-input name="ADMITTED"
                                                                        titleName="Admitted"
                                                                        wire:model.live='ADMITTED' />
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <livewire:select-option name="SCHEDULE_TYPE"
                                                                :options="$scheduleTypeList" :zero="true"
                                                                titleName="Schedule Type"
                                                                wire:model.live='SCHEDULE_TYPE' vertical="true" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_MON" titleName="Monday"
                                                                wire:model='FIX_MON' />
                                                        </div>

                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_TUE"
                                                                titleName="Tuesday" wire:model='FIX_TUE' />
                                                        </div>

                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_WEN"
                                                                titleName="Wensday" wire:model='FIX_WEN' />
                                                        </div>

                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_THU"
                                                                titleName="Thursday" wire:model='FIX_THU' />
                                                        </div>

                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_FRI" titleName="Friday"
                                                                wire:model='FIX_FRI' />
                                                        </div>

                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_SAT"
                                                                titleName="Saturday" wire:model='FIX_SAT' />
                                                        </div>
                                                        <div class="col-md-1">
                                                            <livewire:checkbox-input name="FIX_SUN" titleName="Sunday"
                                                                wire:model='FIX_SUN' />
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
                                            <button type="submit"
                                                class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}</button>
                                        </div>
                                        <div class="text-right col-6 col-md-6">
                                            @if ($ID > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('maintenancecontactpatients_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                            @endif
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
</div>
