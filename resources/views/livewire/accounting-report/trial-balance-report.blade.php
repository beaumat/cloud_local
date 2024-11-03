<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportsaccountingtrial_balance_report') }}"> Trial Balance
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
                                        <livewire:date-input name="DATE" titleName="Date as of"
                                            wire:model.live='DATE' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">

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
                <div class="col-md-6" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-xl table-bordered table-hover">
                        <thead class="bg-sky">
                            <tr>
                                <th class="col-6 text-left">Account Title</th>
                                <th class="col-3 text-right">Debit</th>
                                <th class="col-3 text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->ACCOUNT_TITLE }}</td>
                                    @php
                                        $TOTAL_DEBIT = $TOTAL_DEBIT + $list->TX_DEBIT;
                                        $TOTAL_CREDIT = $TOTAL_CREDIT + $list->TX_CREDIT;
                                    @endphp
                                    <td class="text-right">
                                        {{ $list->TX_DEBIT != 0 ? number_format($list->TX_DEBIT, 2) : '' }}</td>
                                    <td class="text-right">
                                        {{ $list->TX_CREDIT != 0 ? number_format($list->TX_CREDIT, 2) : '' }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td> </td>
                                <td class="text-right font-weight-bold text-primary">
                                    {{ number_format($TOTAL_DEBIT, 2) }}
                                </td>
                                <td class="text-right font-weight-bold text-primary">
                                    {{ number_format($TOTAL_CREDIT, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>
