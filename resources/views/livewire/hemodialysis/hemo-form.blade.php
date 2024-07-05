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
                                    <a class="text-white" href="{{ route('patientshemo') }}">
                                        @if ($ID == 0)
                                            Create
                                        @endif
                                        Hemodialysis Treatment
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
                                            @if ($ID == 0)
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
                                                    @if ($ID == 0)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif

                                                </div>
                                                <div class="col-md-4">
                                                    @if ($ID == 0)
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
                                            @if ($STATUS == 1)
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info"
                                                    @if ($STATUS > 1) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>

                                                @if ($IsPostedButton == true)
                                                    @if ($ID > 0 && $STATUS == 1)
                                                        <button type="button" wire:click='getPosted()'
                                                            class="btn btn-sm btn-warning"
                                                            wire:confirm="Are you sure you want to post?">
                                                            <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                        </button>
                                                    @endif
                                                @endif

                                            @endif
                                        @endif
                                    </div>

                                    <div class="text-right col-6 col-md-6">

                                        @if ($ID > 0 && $STATUS > 1)
                                            @can('patient.treatment.create')
                                                <a id="new" title="Create" href="{{ route('patientshemo_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endcan
                                        @endif
                                        <a target="_blank" href="{{ route('patientshemo_print', ['id' => $ID]) }}"
                                            class="btn btn-sm btn-success">Print</a>

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
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <table class="table  table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky">
                                        <tr>
                                            <th class="col-4">Title</th>
                                            <th class="text-center col-4">Last
                                                Treatment
                                            </th>
                                            <th class="text-center col-4">Today
                                                Treatment
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead class="text-xs">
                                        <tr>
                                            <th></th>
                                            <th class="text-center">
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        PRE
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        POST
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="text-center">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        PRE
                                                    </div>
                                                    <div class="col-md-6">
                                                        POST
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        <tr>
                                            <td class="text-primary">WEIGHT</td>
                                            <td>
                                                <div class="row" id="LAST">
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_PRE_WEIGHT }}
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_POST_WEIGHT }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row" id="TODAY">
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='PRE_WEIGHT'
                                                            class="text-xs w-100 text-right" />

                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='POST_WEIGHT'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">BLOOD PRESSURE</td>
                                            <td>
                                                <div class="row" id="LAST">
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_PRE_BLOOD_PRESSURE }} |
                                                        {{ $OLD_PRE_BLOOD_PRESSURE2 }}
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_POST_BLOOD_PRESSURE }} |
                                                        {{ $OLD_POST_BLOOD_PRESSURE2 }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row" id="TODAY">
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <input
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='PRE_BLOOD_PRESSURE' type="text"
                                                                    class="text-xs w-100 text-right" />
                                                            </div>

                                                            <div class="col-sm-6">
                                                                <input
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='PRE_BLOOD_PRESSURE2' type="text"
                                                                    class="text-xs w-100 text-right" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-sm-6">
                                                                <input
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='POST_BLOOD_PRESSURE' type="text"
                                                                    class="text-xs w-100 text-right" />
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <input
                                                                    @if (!$Modify) disabled @endif
                                                                    wire:model='POST_BLOOD_PRESSURE2' type="text"
                                                                    class="text-xs w-100 text-right" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">HEART RATE</td>
                                            <td>
                                                <div class="row" id="LAST">
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_PRE_HEART_RATE }}
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_POST_HEART_RATE }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row" id="TODAY">
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='PRE_HEART_RATE'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='POST_HEART_RATE'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">O2 SATURATION</td>
                                            <td>
                                                <div class="row" id="LAST">
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_PRE_O2_SATURATION }}
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_POST_O2_SATURATION }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row" id="TODAY">
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='PRE_O2_SATURATION'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='POST_O2_SATURATION'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-primary">TEMPERATURE</td>
                                            <td>
                                                <div class="row" id="LAST">
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_PRE_TEMPERATURE }}
                                                    </div>
                                                    <div class="col-md-6 text-center">
                                                        {{ $OLD_POST_TEMPERATURE }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row" id="TODAY">
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='PRE_TEMPERATURE'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text"
                                                            @if (!$Modify) disabled @endif
                                                            wire:model='POST_TEMPERATURE'
                                                            class="text-xs w-100 text-right" />
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row form-group mt-4 text-right">
                                    <div class="col-md-6">
                                        <label class="text-sm">TIME START NOTES</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" @if (!$Modify) disabled @endif
                                            wire:model='TIME_START' class="form-control form-control-sm" />
                                    </div>
                                </div>

                                <div class="row form-group mt-4 text-right">
                                    <div class="col-md-6">
                                        <label class="text-sm">TIME END NOTES</label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="time" @if (!$Modify) disabled @endif
                                            wire:model='TIME_END' class="form-control form-control-sm" />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fileUpload" class="text-xs">PDF
                                                document
                                                file
                                                @if ($PDF)
                                                    <i class="fa fa-check-circle text-success" aria-hidden="true"></i>
                                                @endif
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <div class="custom-file text-xs">
                                                    <input type="file"
                                                        @if (!$Modify) disabled @endif
                                                        class="custom-file-input text-xs" id="fileUpload"
                                                        wire:model.live='PDF'>
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
                                    <div class="col-md-12">
                                        @if ($FILE_PATH)
                                            <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Preview
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6"
                        @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                        @livewire('Hemodialysis.InventoryTreatment', ['HEMO_ID' => $ID, 'STATUS' => $STATUS, 'LOCATION_ID' => $LOCATION_ID,'ActiveRequired' => $ActiveRequired])
                    </div>
                </div>
            </div>
        </section>
    @endif


</div>
