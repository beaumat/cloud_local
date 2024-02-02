<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenancecontactsupplier') }}"> Supplier </a></h5>
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
                                        <div class="col-md-8">
                                            <livewire:text-input name="NAME" titleName="Supplier Name"
                                                wire:model='NAME' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="ACCOUNT_NO" titleName="Account No."
                                                wire:model='ACCOUNT_NO' />
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs text-sm" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-content-below-general-info-tab"
                                            data-toggle="pill" href="#custom-content-below-general-info" role="tab"
                                            aria-controls="custom-content-below-general-info"
                                            aria-selected="true">General Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-tax-info-tab" data-toggle="pill"
                                            href="#custom-content-below-tax-info" role="tab"
                                            aria-controls="custom-content-below-tax-info" aria-selected="false">Tax
                                            Info</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-content-below-add-info-tab" data-toggle="pill"
                                            href="#custom-content-below-add-info" role="tab"
                                            aria-controls="custom-content-below-add-info" aria-selected="false">Addional
                                            Info</a>
                                    </li>

                                </ul>
                                <div class="tab-content text-sm bg-light" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-general-info"
                                        role="tabpanel" aria-labelledby="custom-content-below-general-info-tab">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <livewire:text-input name="NAME" titleName="Company Name"
                                                        wire:model='COMPANY_NAME' />
                                                </div>
                                                <div class="col-md-2">

                                                    <div class="mt-2">
                                                        <label for="title" class="text-sm">Title</label>
                                                        <select wire:model='SALUTATION'
                                                            class="form-control form-control-sm" name="SALUTATION">
                                                            <option value=""></option>
                                                            <option value="Dr">Dr</option>
                                                            <option value="Miss">Miss</option>
                                                            <option value="Mr.">Mr.</option>
                                                            <option value="Mr.">Ms.</option>
                                                            <option value="Mr.">Prof</option>
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="FIRST_NAME" titleName="First Name"
                                                        wire:model='FIRST_NAME' />
                                                </div>
                                                <div class="col-md-2">
                                                    <livewire:text-input name="MIDDLE_NAME" titleName="M.I"
                                                        wire:model='MIDDLE_NAME' />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="LAST_NAME" titleName="Last Name"
                                                        wire:model='LAST_NAME' />
                                                </div>
                                                <div class="col-md-12">
                                                    <livewire:text-input name="PRINT_NAME_AS" titleName="Print As"
                                                        wire:model='PRINT_NAME_AS' />
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="mt-2">
                                                                        <label for="postal-address"
                                                                            class="text-sm">Postal
                                                                            Address</label>
                                                                        <textarea type="text" autocomplete="off" wire:model='POSTAL_ADDRESS' class="text-sm form-control form-control-sm"
                                                                            id="pos_tal_address" rows="7"></textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-12">
                                                                    <livewire:text-input name="EMAIL"
                                                                        titleName="Email" wire:model='EMAIL' />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <livewire:text-input name="CONTACT_PERSON"
                                                                        titleName="Contact Person"
                                                                        wire:model='CONTACT_PERSON' />
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <livewire:text-input name="TELEPHONE_NO"
                                                                        titleName="Telephone Number"
                                                                        wire:model='TELEPHONE_NO' />
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <livewire:text-input name="FAX_NO"
                                                                        titleName="Fax Number" wire:model='FAX_NO' />

                                                                </div>
                                                                <div class="col-md-12">
                                                                    <livewire:text-input name="MOBILE_NO"
                                                                        titleName="Mobile Number"
                                                                        wire:model='MOBILE_NO' />

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade" id="custom-content-below-tax-info" role="tabpanel"
                                        aria-labelledby="custom-content-below-tax-info-tab">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <livewire:text-input name="TAXPAYER_ID"
                                                        titleName="Taxpayer ID No." wire:model='TAXPAYER_ID' />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:select-option name="TAX_ID" :options="$taxList"
                                                        :zero="true" titleName="Tax Account" wire:model='TAX_ID'
                                                        :key="$taxList->pluck('ID')->join('_')" />

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-content-below-add-info" role="tabpanel"
                                        aria-labelledby="custom-content-below-add-info-tab">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <livewire:select-option name="GROUP_ID" :options="$contactGroup"
                                                        :zero="true" titleName="Group" wire:model='GROUP_ID'
                                                        :key="$taxList->pluck('ID')->join('_')" />
                                                </div>
                                                <div class="col-md-6">
                                                    <livewire:select-option name="PAYMENT_TERMS_ID" :options="$paymentTermList"
                                                    :zero="true" titleName="Payment Terms" wire:model='PAYMENT_TERMS_ID'
                                                    :key="$taxList->pluck('ID')->join('_')" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                wire:model='INACTIVE' />
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
                                                href="{{ route('maintenancecontactsupplier_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
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
