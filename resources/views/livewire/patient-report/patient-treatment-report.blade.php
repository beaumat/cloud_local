<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportspatient_treatment_report') }}"> Patient Treatment Report
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
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Year:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control form-control-sm" wire:model='YEAR' />
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='reload()'
                                            class="btn btn-xs btn-warning w-100 ">Reload</button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3 text-right">
                                        <label>Month:</label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-control form-control-sm" name="MONTH"
                                            wire:model.live='MONTH'>
                                            @foreach ($monthList as $item)
                                                <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <button wire:click='generate()'
                                            class="btn btn-xs btn-primary w-100">Generate</button>
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


                </div>
            </div>
            <div class="row">
                {{-- header --}}

                <table class="table table-sm table-bordered table-hover">
                    <thead class='text-xs bg-sky'>
                        <tr>
                            <td>Patient</td>
                            @foreach ($dailyList as $day)
                                <td class="text-center">
                                    {{ date('d', strtotime($day)) }}<br />{{ date('D', strtotime($day)) }}</td>
                            @endforeach
                            <td>Total</td>
                        </tr>
                    </thead>
                    <tbody class="text-xs">



                        @foreach ($dataList as $list)
                            <tr>
                                <td>{{ $list->PATIENT_NAME }}</td>
                                @foreach ($dailyList as $day)
                                    <td>{{ $list[date('d', strtotime($day))] }}</td>
                                @endforeach

                                <td>100</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>



            </div>
        </div>
    </section>
</div>
