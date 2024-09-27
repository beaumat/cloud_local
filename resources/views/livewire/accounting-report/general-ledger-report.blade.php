<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsaccountinggeneral_ledeger_report') }}"> General Ledger
                            Report
                        </a></h5>
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
                                        <livewire:date-input name="DATE_FROM" titleName="Date From"
                                            wire:model.live='DATE_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TO" titleName="Date To"
                                            wire:model.live='DATE_TO' :isDisabled="false" />
                                    </div>
                                    <div class='col-md-12 mt-1'>
                                        <div class="form-group">
                                            <button class="btn btn-danger btn-xs w-25"
                                                wire:click='generate()'>Generate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <livewire:select-option name="ACCOUNT_ID" titleName="Account " :options="$accountList"
                                            :zero="true" :isDisabled=false wire:model='ACCOUNT_ID' />

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
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="bg-sky text-xs">
                            <tr>
                                <th class="col-3">Account Title</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Reference No.</th>
                                <th class="col-2">Name/Details</th>
                                <th>Location</th>
                                <th class="col-2">Notes</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                                <th class="text-right">Balance</th>

                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->ACCOUNT_TITLE }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td>{{ $list->TX_CODE }}</td>
                                    <td>{{ $list->TX_NAME }}</td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td>{{ $list->TX_NOTES }}</td>
                                    @php
                                        if ($list->DEBIT > 0) {
                                            $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT;
                                        }

                                        if ($list->CREDIT > 0) {
                                            $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT;
                                        }
                                    @endphp

                                    <td class="text-right">{{ $list->DEBIT > 0 ? number_format($list->DEBIT) : '' }}
                                    </td>
                                    <td class="text-right">{{ $list->CREDIT > 0 ? number_format($list->CREDIT) : '' }}
                                    </td>

                                    @php
                                        if ($list->DEBIT > 0) {
                                            $BALANCE = $BALANCE + $list->DEBIT ?? 0;
                                        } else {
                                            $BALANCE = $BALANCE - $list->CREDIT ?? 0;
                                        }
                                    @endphp
                                    <td class="font-weight-bold text-right">{{ number_format($BALANCE, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td class="text-sm text-primary text-right">{{ number_format($TOTAL_DEBIT, 2) }}</td>
                                <td class="text-sm text-primary text-right">{{ number_format($TOTAL_CREDIT, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
