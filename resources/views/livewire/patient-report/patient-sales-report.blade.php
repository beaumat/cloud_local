<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('vendorsbills') }}"> Patient Collection Report </a></h5>
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
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_COLLECTION_FROM" titleName="Collection From"
                                            wire:model.live='DATE_COLLECTION_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_COLLECTION_TO" titleName="Collection To"
                                            wire:model.live='DATE_COLLECTION_TO' :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_FROM" titleName="Transaction From"
                                            wire:model.live='DATE_TRANSACTION_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_TO" titleName="Transaction To"
                                            wire:model.live='DATE_TRANSACTION_TO' :isDisabled="false" />
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-0">
                                    <label class="text-xs pt-2">Location:</label>
                                    <select name="location" wire:model.live='LOCATION_ID'
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
                    <div class="form-group">
                        <button class="btn btn-sm btn-success" wire:click='showfilter()'>
                            Filter
                        </button>
                        <button class="btn btn-sm btn-warning" wire:click='resetFilter()'>
                            Reset
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <th>(SC) Date</th>
                                <th>(SC) Code</th>
                                <th>Patient Name</th>
                                <th>Item Name</th>
                                <th>(SC) Amount</th>
                                <th>(P) Date</th>
                                <th>(P) Code</th>
                                <th>(P) Method</th>
                                <th>(P) Deposit</th>
                                <th>(P) Paid </th>
                                <th>Bal.</th>
                                <th>Doctor</th>
                                <th>Location </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                @php

                                    if ($sc_code == $list->SC_CODE) {
                                        $is_sc = false;
                                    } else {
                                        $is_sc = true;
                                    }

                                    if ($tempName == $list->PATIENT_NAME) {
                                        $is_add = false;
                                        $running_balance =
                                            $running_balance + $list->SC_AMOUNT ?? (0 - $list->PP_PAID ?? 0);
                                    } else {
                                        $is_add = true;
                                        $is_sc = true;
                                        $running_balance = $list->SC_AMOUNT ?? (0 - $list->PP_PAID ?? 0);
                                    }

                                    $tempName = $list->PATIENT_NAME;
                                    $sc_code = $list->SC_CODE;

                                @endphp
                                <tr class=" @if ($is_add == true) font-weight-bold @endif">
                                    <td>
                                        @if ($is_sc == true)
                                            <a target="_BLANK"
                                                href="{{ route('patientsservice_charges_edit', ['id' => $list->SC_ID]) }}">{{ $list->SC_CODE }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($is_sc == true)
                                            {{ date('m/d/Y', strtotime($list->SC_DATE)) }}
                                        @endif
                                    </td>

                                    <td>
                                        @if ($is_add == true)
                                            {{ $list->PATIENT_NAME }}
                                        @endif
                                    </td>
                                    <td>{{ $list->ITEM_NAME }}</td>
                                    <td class="text-right">

                                        {{ number_format($list->SC_AMOUNT, 2) }}

                                    </td>
                                    <td>
                                        @if ($list->PP_DATE)
                                            {{ date('m/d/Y', strtotime($list->PP_DATE)) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($list->PP_ID)
                                            <a target="_BLANK"
                                                href="{{ route('patientspayment_edit', ['id' => $list->PP_ID]) }}">{{ $list->PP_CODE }}</a>
                                        @endif
                                    </td>
                                    <td>{{ $list->PAYMENT_METHOD }}</td>
                                    <td class="text-right">
                                        @if ($list->PP_DEPOSIT > 0)
                                            {{ number_format($list->PP_DEPOSIT, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($list->PP_PAID > 0)
                                            {{ number_format($list->PP_PAID, 2) }}
                                        @endif
                                    </td>
                                    <td>

                                        {{ number_format($running_balance, 2) }}
                                    </td>
                                    <td>
                                        @if ($is_add == true)
                                            {{ $list->DOCTOR_NAME }}
                                        @endif
                                    </td>
                                    <td>{{ $list->LOCATION_NAME }}</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>


                </div>
                <div class="col-md-6">

                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <h6> <label>TOTAL SERVICE CHARGE : </label> {{ number_format($TOTAL_CHARGE, 2) }}</h6>
                            <h6> <label>TOTAL COLLECTION : </label> {{ number_format($TOTAL_PAID, 2) }}</h6>
                            <h6> <label>TOTAL BALANCE : </label> {{ number_format($TOTAL_CHARGE - $TOTAL_PAID, 2) }}
                            </h6>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
