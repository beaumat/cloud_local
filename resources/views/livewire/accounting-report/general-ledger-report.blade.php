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
                                            <button class="btn btn-danger btn-xs w-25" wire:loading.attr='disabled'
                                                wire:click='generate()'>Generate</button>
                                            <button class="btn btn-success btn-xs w-25" wire:loading.attr='disabled'
                                                wire:click='export()'>Export</button>

                                            <div wire:loading.delay>
                                                <span class='spinner'></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <livewire:select-checkbox name="ACCOUNT_ID" titleName="Filter Account" :options="$accountList"
                                    :zero="true" :isDisabled=false wire:model='selectedAccount' />
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <livewire:select-checkbox name="ACCOUNT_TYPE_ID" titleName="Filter Account Type"
                                            :options="$accountTypeList" :zero="true" :isDisabled=false
                                            wire:model='selectedAccountType' />
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
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <table class="table table-sm table-bordered table-hover">
                        <thead class="bg-sky h1">
                            <tr>

                                <th>Type</th>
                                <th>Date</th>
                                <th class="col-1">Reference No.</th>
                                <th class="col-3">Name/Details</th>
                                <th>Location</th>
                                <th class="col-3">Notes</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                                <th class="text-right">Balance</th>

                            </tr>
                        </thead>
                        <tbody class="h1">
                            @php
                                $TEMP_ACCOUNT = '';
                                $TEMP_DEBIT = 0;
                                $TEMP_CRFDIT = 0;
                            @endphp
                            @foreach ($dataList as $list)
                                @if ($TEMP_ACCOUNT == '')
                                    @php
                                        $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                                        $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                                        $TEMP_CRFDIT = (float) $list->CREDIT ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="text-primary font-weight-bold text-md">{{ $TEMP_ACCOUNT }}</td>
                                    </tr>
                                @else
                                    @if ($TEMP_ACCOUNT != $list->ACCOUNT_TITLE)
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right text-info ">
                                                <div class="border-top border-secondary">
                                                    {{ $TEMP_DEBIT > 0 ? number_format($TEMP_DEBIT, 2) : '0.00' }}
                                                </div>
                                            </td>
                                            <td class="text-right text-info top-line">
                                                <div class="border-top border-secondary">
                                                    {{ $TEMP_CREDIT > 0 ? number_format($TEMP_CREDIT, 2) : '0.00' }}
                                                </div>
                                            </td>
                                            <td></td>
                                        </tr>
                                        @php
                                            $TEMP_ACCOUNT = $list->ACCOUNT_TITLE;
                                            $TEMP_DEBIT = (float) $list->DEBIT ?? 0;
                                            $TEMP_CREDIT = (float) $list->CREDIT ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-primary font-weight-bold text-md">{{ $TEMP_ACCOUNT }}</td>
                                        </tr>
                                    @else
                                        @php
                                            $TEMP_DEBIT += (float) $list->DEBIT ?? 0;
                                            $TEMP_CREDIT += (float) $list->CREDIT ?? 0;
                                        @endphp
                                    @endif
                                @endif

                                <tr>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td>
                                        @if ($list->TX_CODE)
                                            <span class="text-primary" type="button"
                                                wire:click='openDetails({{ $list->JOURNAL_NO }})'>
                                                {{ $list->TX_CODE }} </span>
                                        @else
                                            <b class="text-danger">{{ 'ERR_NO_ID: ' . $list->ID }}</b>
                                        @endif
                                    </td>
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

                                    <td class="text-right">{{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                    </td>
                                    <td class="text-right">
                                        {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
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
                                <td class="text-right text-info">
                                    <div class="border-top border-secondary">
                                        {{ $TEMP_DEBIT > 0 ? number_format($TEMP_DEBIT, 2) : '0.00' }} </div>
                                </td>
                                <td class="text-right text-info">
                                    <div class="border-top border-secondary">
                                        {{ $TEMP_CREDIT > 0 ? number_format($TEMP_CREDIT, 2) : '0.00' }} </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
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
