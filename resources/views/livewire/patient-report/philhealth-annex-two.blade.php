<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsphilhealth_annex_report') }}">Philhealth Annex D (IBNR)
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
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-1 text-right">

                                    </div>
                                    <div class="col-1">

                                    </div>
                                    <div class="col-1 text-right">

                                    </div>
                                    <div class="col-2">

                                    </div>
                                    <div class="col-2">
                                        <button wire:click='generateB()' class="btn btn-xs btn-primary w-100">
                                            Generate
                                        </button>
                                    </div>
                                    <div class='col-2'>

                                    </div>
                                    <div class='col-2'>


                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8 text-right">
                                        <label class="text-xs ">Location:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">

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
                <div class="col-md-12">
                    <div style="max-height: 75vh; overflow-y: auto;">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs  sticky-header">

                                @if ($columnType == 1)
                                    <tr>
                                        <td class=" bg-dark"></td>
                                        <td class="col-1 bg-dark"><button wire:click='autoSet()'
                                                wire:confirm='Are you sure?' class="btn btn-xs btn-success w-100">Auto
                                                Set Code</button></td>
                                        <td class=" bg-primary"></td>
                                        <td class=" bg-primary text-center">Patient</td>
                                        <td class=" bg-primary"></td>
                                        <td class=" bg-info"></td>
                                        <td class="bg-info text-center">Member</td>
                                        <td class="bg-info"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                    </tr>
                                    <tr>
                                        <td class=" bg-dark">Item No.</td>
                                        <td class="col-1 bg-dark">Claims/Code Reference</td>
                                        <td class=" bg-primary">Surname</td>
                                        <td class=" bg-primary">Firstname</td>
                                        <td class=" bg-primary">Middlename</td>
                                        <td class="bg-info">Surname</td>
                                        <td class=" bg-info">Firstname</td>
                                        <td class=" bg-info">Middlename</td>
                                        <td class="col-1 bg-dark">Member's PIN</td>
                                        <td class="col-1 bg-dark">Date of Admission</td>
                                        <td class="col-1 bg-dark">Date of Discharged</td>
                                        <td class="col-1 bg-dark">Case Rate/ Claim Amount</td>
                                        <td class="col-1 bg-dark">ICD 10/RVS code</td>
                                        <td class="col-1 bg-dark">*Claim Status</td>
                                    </tr>
                                @endif

                                @if ($columnType == 2)
                                    <tr>
                                        <td class=" bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class=" bg-primary"></td>
                                        <td class=" bg-primary text-center">Patient</td>
                                        <td class=" bg-primary"></td>
                                        <td class=" bg-info"></td>
                                        <td class="bg-info text-center">Member</td>
                                        <td class="bg-info"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                        <td class="col-1 bg-dark"></td>
                                    </tr>
                                    <tr>
                                        <td class=" bg-dark">Item No.</td>
                                        <td class="col-1 bg-dark">Yr. Start From.</td>
                                        <td class="col-1 bg-dark">Claims Series Reference</td>
                                        <td class=" bg-primary">Surname</td>
                                        <td class=" bg-primary">Firstname</td>
                                        <td class=" bg-primary">Middlename</td>
                                        <td class="bg-info">Surname</td>
                                        <td class=" bg-info">Firstname</td>
                                        <td class=" bg-info">Middlename</td>
                                        <td class="col-1 bg-dark">Member's PIN</td>
                                        <td class="col-1 bg-dark">Date of Admission</td>
                                        <td class="col-1 bg-dark">Date of Discharged</td>
                                        <td class="col-1 bg-dark">Date of Filed</td>
                                        <td class="col-1 bg-dark">Date of Refiled</td>
                                        <td class="col-1 bg-dark">ICD 10/RVS code</td>
                                        <td class="col-1 bg-dark">Case Rate/ Claim Amount</td>
                                        <td class="col-1 bg-dark">*Claim Status</td>
                                    </tr>
                                @endif

                            </thead>
                            <tbody class="text-xs">
                                @php

                                    $r = 0;
                                @endphp
                                @if ($columnType == 1)
                                    @foreach ($dataList as $list)
                                        @php
                                            $r++;

                                        @endphp
                                        <tr>
                                            <td>{{ $r }}</td>
                                            <td>{{ $list->CLAIM_NO }}</td>
                                            <td>{{ $list->LAST_NAME }}</td>
                                            <td>{{ $list->FIRST_NAME }}</td>
                                            <td>{{ $list->MIDDLE_NAME }}</td>
                                            @if ($list->IS_PATIENT)
                                                <td>{{ $list->LAST_NAME }}</td>
                                                <td>{{ $list->FIRST_NAME }}</td>
                                                <td>{{ $list->MIDDLE_NAME }}</td>
                                            @else
                                                <td>{{ $list->MEMBER_LAST_NAME }}</td>
                                                <td>{{ $list->MEMBER_FIRST_NAME }}</td>
                                                <td>{{ $list->MEMBER_MIDDLE_NAME }}</td>
                                            @endif

                                            <td>{{ $list->PIN_NO }}</td>
                                            <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td>{{ number_format($list->P1_TOTAL, 2) }}</td>
                                            <td>90935</td>
                                            <td>FOR FILE</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($columnType == 2)

                                    @foreach ($dataList as $list)
                                        @php
                                            $r++;

                                        @endphp
                                        <tr>
                                            <td>{{ $r }}</td>
                                            <td>{{ $YEAR }}</td>
                                            <td>{{ $list->AR_NO }}</td>
                                            <td>{{ $list->LAST_NAME }}</td>
                                            <td>{{ $list->FIRST_NAME }}</td>
                                            <td>{{ $list->MIDDLE_NAME }}</td>
                                            @if ($list->IS_PATIENT)
                                                <td>{{ $list->LAST_NAME }}</td>
                                                <td>{{ $list->FIRST_NAME }}</td>
                                                <td>{{ $list->MIDDLE_NAME }}</td>
                                            @else
                                                <td>{{ $list->MEMBER_LAST_NAME }}</td>
                                                <td>{{ $list->MEMBER_FIRST_NAME }}</td>
                                                <td>{{ $list->MEMBER_MIDDLE_NAME }}</td>
                                            @endif

                                            <td>{{ $list->PIN_NO }}</td>
                                            <td>{{ date('M/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                            <td>{{ date('M/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                            <td>{{ date('M/d/Y', strtotime($list->AR_DATE)) }}</td>
                                            <td>N/A</td>
                                            <td>90935</td>
                                            <td>{{ number_format($list->P1_TOTAL, 2) }}</td>
                                            <td>In Progress</td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>
    </section>
</div>
