<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_inventory_report') }}"> Patient Inventory Report </a>
                    </h5>
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
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_FROM" titleName="From" wire:model='DATE_FROM'
                                            :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TO" titleName="To" wire:model='DATE_TO'
                                            :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                                <button class="btn btn-xs btn-danger w-25" wire:click='generate()'
                                    wire:loading.attr='disabled'>Generate</button>
                                {{-- <button class="btn btn-xs btn-success w-25" wire:click='export()'
                                    wire:loading.attr='disabled'>Export</button> --}}
                                {{-- <a type="button" class="btn btn-xs btn-warning w-25"
                                    href="{{ route('reportspatient_sales_report_print', ['date_from' => $DATE_TRANSACTION_FROM, 'date_to' => $DATE_TRANSACTION_TO, 'location_id' => $LOCATION_ID]) }}"
                                    target="_BLANK">
                                    Print
                                </a> --}}
                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                         

                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="PATIENT_ID1" titleName="Filter patient"
                                        :options="$filterPatient" :zero="true" :isDisabled=false
                                        wire:model='selectedPatient' />
                                @else
                                    <livewire:select-checkbox name="PATIENT_ID2" titleName="Filter patient"
                                        :options="$filterPatient" :zero="true" :isDisabled=false
                                        wire:model='selectedPatient' />
                                @endif
                            </div>
                            <div class="col-4">
                         

                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="ITEM1" titleName="Filter item" :options="$filterItem"
                                        :zero="true" :isDisabled=false wire:model='selectedItem' />
                                @else
                                    <livewire:select-checkbox name="ITEM2" titleName="Filter item" :options="$filterItem"
                                        :zero="true" :isDisabled=false wire:model='selectedItem' />
                                @endif
                            </div>
                            <div class="col-4">
                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="METHOD1" titleName="Filter method"
                                        :options="$filterMethod" :zero="true" :isDisabled=false
                                        wire:model='selectedMethod' />
                                @else
                                    <livewire:select-checkbox name="METHOD2" titleName="Filter method"
                                        :options="$filterMethod" :zero="true" :isDisabled=false
                                        wire:model='selectedMethod' />
                                @endif
                            </div>
                            <div class="col-6 p-1">
                                <button class="btn btn-xs btn-info w-25" wire:click='shortFilter()'
                                    wire:loading.attr='disabled'>Filter</button>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th>Patient Name</th>
                                <th>Item Name</th>
                                <th class="bg-info">(SC) Date</th>
                                <th class="bg-info">(SC) Code</th>
                                <th class="bg-info">(SC) Amount</th>
                                <th class="bg-success">(P) Date</th>
                                <th class="bg-success">(P) Code</th>
                                <th class="bg-success">(P) Method</th>
                                <th class="bg-success">(P) Deposit</th>
                                <th class="bg-success">(P) Paid </th>
                                <th class="bg-danger">Running Bal.</th>
                                <th>Doctor</th>
                                <th>Location </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
