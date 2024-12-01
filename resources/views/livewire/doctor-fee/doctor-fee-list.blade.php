<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_sales_report') }}"> Doctor Professional Fee
                        </a>
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
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <button class="btn btn-sm btn-danger" wire:click='Generate()'>Generate</button>
                                    </div>
                                    <div class="col-md-3 text-md-right">
                                        <label class="text-sm text-primary">Year </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control form-control-sm" wire:model='YEAR' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-11">
                                        @if ($refreshComponent)
                                            <livewire:select-checkbox name="FilterPP1" titleName="Period"
                                                :options="$filterPaymentPeriod" :zero="true" :isDisabled=false :vertical=true
                                                wire:model='SelectedPaymentPeriod' />
                                        @else
                                            <livewire:select-checkbox name="FilterPP2" titleName="Period"
                                                :options="$filterPaymentPeriod" :zero="true" :isDisabled=false :vertical=true
                                                wire:model='SelectedPaymentPeriod' />
                                        @endif
                                    </div>
                                    <div class="col-1 ">
                                        <button class="btn btn-primary btn-sm" wire:click='filterPeriod()'>
                                            Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">

                                    <div class='col-4  text-md-right'>
                                        <label class="text-xs pt-2">Location:</label>
                                    </div>
                                    <div class="col-8">
                                        <select
                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                            name="location" wire:model.live='LOCATION_ID'
                                            class="form-control form-control-sm text-xs mt-1">
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                            </div>
                            <div class="col-6 text-right">
                                {{-- <button class="btn btn-sm btn-success " wire:click='export()'> Export </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" style="max-height: 80vh; overflow-y: auto;">

                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            @if ($headerList)
                                <tr class="bg-sky">

                                    <th class="col-3"></th>
                                    @foreach ($headerList as $list)
                                        <th class="text-white">
                                            {{ date('M/d', strtotime($list['DATE_FROM'])) }}&nbsp;-&nbsp;{{ date('M/d', strtotime($list['DATE_TO'])) }}
                                        </th>
                                    @endforeach
                                    <th class="text-right">Total</th>
                                </tr>
                                <tr class="bg-info">
                                    <th class="col-3 bg-warning">Nephro Name</th>
                                    @foreach ($headerList as $list)
                                        <th class="text-white">{{ $list['RECEIPT_NO'] }}
                                        </th>
                                    @endforeach
                                    <th class="text-right">Balance</th>
                                </tr>
                            @endif
                        </thead>
                        @php
                            $grandtotal = 0;
                        @endphp
                        <tbody class="text-xs">
                            @foreach ($doctorList as $list)
                                <tr>
                                    <td>{{ $list['DOCTOR_NAME'] }}</td>
                                    @php   
                                       $total = 0;
                                    @endphp

                                    @for ($n = 1; $n <= $row; $n++)
                                        @php
                                            $total = $total + $list[$n] ?? 0;
                                        @endphp
                                        <td class='text-right'>{{ number_format($list[$n], 2) }}</td>
                                    @endfor
                                    <td class="text-right">{{ number_format($total, 2) }}</td>
                                    @php
                                        $grandtotal = $grandtotal + $total ?? 0;
                                    @endphp
                                </tr>
                            @endforeach

                            <tr>
                                <td class="font-weight-bold text-primary">TOTAL</td>
                                @for ($n = 1; $n <= $row; $n++)
                                    <td class='text-right font-weight-bold text-primary'>
                                    {{ number_format($totalList[$n], 2) }}
                                    </td>
                                @endfor
                                <td class="text-right font-weight-bold text-primary">{{ number_format($grandtotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @livewire('DoctorFee.DoctorFeeForm')
    @livewire('DoctorFee.DoctorFeeReceivedModal')
</div>
