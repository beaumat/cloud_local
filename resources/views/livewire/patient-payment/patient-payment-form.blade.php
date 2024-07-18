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
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('patientspayment') }}"> Patient Payments </a>
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
                                                <livewire:select-option name="PATIENT_ID" titleName="Patient"
                                                    :options="$contactList" :zero="true" :isDisabled=false
                                                    wire:model='PATIENT_ID' />
                                            @else
                                                <livewire:select-option name="PATIENT_ID" titleName="Patient"
                                                    :options="$contactList" :zero="true" :isDisabled=true
                                                    wire:model='PATIENT_ID' />
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @if ($Modify && $AMOUNT_APPLIED  == 0)
                                                        <livewire:number-input name="AMOUNT" titleName="Amount"
                                                            :isDisabled=false wire:model='AMOUNT' />
                                                    @else
                                                        <livewire:number-input name="AMOUNT" titleName="Amount"
                                                            :isDisabled=true wire:model='AMOUNT' />
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if ($Modify)
                                                        <livewire:dropdown-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled=false titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="false"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @else
                                                        <livewire:dropdown-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled=true titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="false"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                @if ($reloadType)
                                                    @if ($showReceiptNo)
                                                        <div class="col-md-6">
                                                            @if ($Modify)
                                                                <livewire:text-input name="RECEIPT_REF_NO"
                                                                    :titleName=$TITLE_REF :isDisabled=false
                                                                    wire:model='RECEIPT_REF_NO' />
                                                            @else
                                                                <livewire:text-input name="RECEIPT_REF_NO"
                                                                    :titleName=$TITLE_REF :isDisabled=true
                                                                    wire:model='RECEIPT_REF_NO' />
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    @if ($showReceiptNo)
                                                        <div class="col-md-6">
                                                            @if ($Modify)
                                                                <livewire:text-input name="RECEIPT_REF_NO"
                                                                    :titleName=$TITLE_REF :isDisabled=false
                                                                    wire:model='RECEIPT_REF_NO' />
                                                            @else
                                                                <livewire:text-input name="RECEIPT_REF_NO"
                                                                    :titleName=$TITLE_REF :isDisabled=true
                                                                    wire:model='RECEIPT_REF_NO' />
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endif
                                                @if ($showReceiptDate)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:date-input name="RECEIPT_DATE" titleName="GL Date"
                                                                wire:model='RECEIPT_DATE' :isDisabled="false" />
                                                        @else
                                                            <livewire:date-input name="RECEIPT_DATE" titleName="GL Date"
                                                                wire:model='RECEIPT_DATE' :isDisabled="true" />
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-4">
                                                    {{-- @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                      
                                                    @endif --}}

                                                    <livewire:text-input name="Code" titleName="No."
                                                        :isDisabled=true wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify && $AMOUNT == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
                                                </div>


                                                @if ($showCardNo)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:text-input name="CARD_NO" titleName="Card No."
                                                                :isDisabled=false wire:model='CARD_NO' />
                                                        @else
                                                            <livewire:text-input name="CARD_NO" titleName="Card No."
                                                                :isDisabled=true wire:model='CARD_NO' />
                                                        @endif
                                                    </div>

                                                @endif

                                                @if ($showCardDateExpire)
                                                    <div class="col-md-6">
                                                        @if ($Modify)
                                                            <livewire:date-input name="CARD_EXPIRY_DATE"
                                                                titleName="Card Expired" wire:model='CARD_EXPIRY_DATE'
                                                                :isDisabled="false" />
                                                        @else
                                                            <livewire:date-input name="CARD_EXPIRY_DATE"
                                                                titleName="Card Expired" wire:model='CARD_EXPIRY_DATE'
                                                                :isDisabled="true" />
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    @if ($Modify)
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=false wire:model='NOTES' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=true wire:model='NOTES' :vertical="false" />
                                                    @endif
                                                </div>


                                                @if ($showFileName && $Modify)
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="fileUpload" class="text-xs">PDF document file
                                                                @if ($PDF)
                                                                    <i class="fa fa-check-circle text-success"
                                                                        aria-hidden="true"></i>
                                                                @endif
                                                            </label>
                                                            <div class="input-group input-group-sm">
                                                                <div class="custom-file text-xs">
                                                                    <input type="file"
                                                                        class="custom-file-input text-xs"
                                                                        id="fileUpload" wire:model='PDF'>
                                                                    <label class="custom-file-label text-xs"
                                                                        for="fileUpload">
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
                                                    class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                        aria-hidden="true"></i> Cancel</button>
                                            @endif
                                        @else
                                            @if ($AMOUNT_APPLIED == 0)
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                            @endif

                                            @if ($showFileName)
                                                @can('patient.payment.print')
                                                    <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Preview
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($showFileName)
                                                @can('patient.payment.update')
                                                    @if (!$IS_CONFIRM)
                                                        <button type="button" wire:click='getConfirm()'
                                                            wire:confirm="Are you sure this guaranteed letter is confirm?"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Confirm
                                                        </button>
                                                    @else
                                                        <label class="text-xs text-primary px-3">
                                                            <i>
                                                                Guarantee Letter Confirm on
                                                                <b class="text-info">{{ \Carbon\Carbon::parse($DATE_CONFIRM)->format('m/d/Y') }}
                                                                </b>
                                                            </i>
                                                        </label>
                                                    @endif
                                                @endif
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @can('patient.payment.create')
                                            @if ($ID > 0 && $STATUS > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('patientspayment_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endif
                                        @endcan

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
                                    <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                        href="#custom-tabs-four-item" role="tab"
                                        aria-controls="custom-tabs-four-item" aria-selected="true">Service Charges</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active " id="custom-tabs-four-item" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-item-tab">
                                    <div class="row"
                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('PatientPayment.PatientPaymentCharges', ['PATIENT_PAYMENT_ID' => $ID, 'PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'STATUS' => $STATUS, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6 text-left">

                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <label class="text-sm">Payment Applied:</label>
                                            <label
                                                class="text-primary text-lg">{{ number_format($AMOUNT_APPLIED, 2) }}</label>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
