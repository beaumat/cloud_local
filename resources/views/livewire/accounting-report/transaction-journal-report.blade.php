<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsaccountingtransaction_journal_report') }}"> Transaction
                            Journal
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
                                                wire:click='generate()'>
                                                Generate
                                            </button>
                                            <button class="btn btn-success btn-xs w-25" wire:loading.attr='disabled'
                                                wire:click='export()'>
                                                Export
                                            </button>

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
                    <table class="table table-sm table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <td>Jrnl#</td>
                                <td>Date</td>
                                <td class="col-1">Type</td>
                                <td class="col-1">Ref #</td>
                                <td class="col-2">Name</td>
                                <td class="col-1">Location</td>
                                <td class="col-2">Account Title</td>
                                <td class="col-3">Particular</td>
                                <th class="col-1 text-right">Debit</th>
                                <th class="col-1 text-right">Credit</th>
                                <th class="col-1">Location</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->JOURNAL_NO }}</td>
                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                    <td>{{ $list->TYPE }}</td>
                                    <td>
                                        <span class="text-primary" type="button"
                                            wire:click='openDetails({{ $list->JOURNAL_NO }})'>
                                            {{ $list->TX_CODE }}</span>
                                    </td>
                                    <td>{{ $list->TX_NAME }}</td>
                                    <td>{{ $list->LOCATION }}</td>
                                    <td>{{ $list->ACCOUNT_TITLE }}</td>
                                    <td>{{ $list->TX_NOTES }}</td>
                                    <td class="text-right">
                                        {{ $list->DEBIT > 0 ? number_format($list->DEBIT, 2) : '' }}
                                        @php
                                            if ($list->DEBIT > 0) {
                                                $TOTAL_DEBIT = $TOTAL_DEBIT + $list->DEBIT ?? 0;
                                            }
                                        @endphp
                                    </td>
                                    <td class="text-right">
                                        {{ $list->CREDIT > 0 ? number_format($list->CREDIT, 2) : '' }}
                                        @php
                                            if ($list->CREDIT > 0) {
                                                $TOTAL_CREDIT = $TOTAL_CREDIT + $list->CREDIT ?? 0;
                                            }
                                        @endphp
                                    </td>
                                    <td> {{ $list->LOCATION }}</td>
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
                                <td></td>
                                <td class="text-right font-weight-bold">
                                    @if ($TOTAL_DEBIT)
                                        {{ number_format($TOTAL_DEBIT, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </td>
                                <td class="text-right font-weight-bold">
                                    @if ($TOTAL_CREDIT)
                                        {{ number_format($TOTAL_CREDIT, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
    {{-- <script>
        window.open("{{ $url }}", "_blank");
    </script> --}}
</div>
