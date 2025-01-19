<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_sales_report') }}">
                            Doctor Professional Fee
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
                                <button class="btn btn-sm btn-danger" wire:click='Generate()'>Generate</button>
                                <button class="btn btn-sm btn-success" wire:click='Export()'>Export</button>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-2  text-right">
                                        <label class="text-sm">From</label>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <input type="date" class="form-control form-control-sm" name="DATE_FROM"
                                                wire:model='DATE_FROM' />
                                        </div>
                                    </div>
                                    <div class="col-2 text-right">
                                        <label class="text-sm">To</label>
                                    </div>
                                    <div class="col-3">
                                        <div>
                                            <input type="date" class="form-control form-control-sm" name="DATE_TO"
                                                wire:model='DATE_TO' />
                                        </div>
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
                <div class="col-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead>
                            @if ($headerList)
                                <tr class="bg-sky">
                                    <th class="col-3">Date Period </th>
                                    @foreach ($headerList as $list)
                                        <th class="text-white text-center">
                                            {{ date('M/d', strtotime($list['DATE_FROM'])) }}&nbsp;-&nbsp;{{ date('M/d', strtotime($list['DATE_TO'])) }}
                                        </th>
                                    @endforeach
                                    <th class=""></th>
                                </tr>
                                <tr class="bg-info">

                                    <th class="col-3">Date O.R</th>
                                    @foreach ($headerList as $list)
                                        <th class=" text-center">
                                            {{ date('m/d/Y', strtotime($list['DATE'])) }}
                                        </th>
                                    @endforeach
                                    <th class=""></th>
                                </tr>
                                <tr class="bg-secondary">
                                    <th class="col-3">O.R Number</th>
                                    @foreach ($headerList as $list)
                                        <th class="font-weight-bold text-center">
                                            {{ $list['RECEIPT_NO'] }}
                                        </th>
                                    @endforeach
                                    <th class="text-right"> Total</th>
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
                                        @if (isset($totalList[$n]))
                                            {{ number_format($totalList[$n], 2) }}
                                        @endif
                                    </td>
                                @endfor
                                <td class="text-right font-weight-bold text-primary">
                                    {{ number_format($grandtotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>
