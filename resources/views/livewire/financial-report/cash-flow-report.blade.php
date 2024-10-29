@php
    use App\Services\CashFlowServices;

@endphp
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialcash_flow_report') }}"> Cash Flow Reports </a>
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
                                    <div class="col-3">
                                        <livewire:number-input name="YEAR" titleName="Year" wire:model.live='YEAR'
                                            :isDisabled="false" />
                                    </div>
                                    <div class="col-md-9">
                                        {{-- <livewire:date-input name="DATE_TO" titleName="Date End"
                                            wire:model.live='DATE_TO' :isDisabled="false" /> --}}
                                    </div>
                                    <div class='col-6 mt-1'>
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
                                        <label class="text-xs mt-1">Modify</label> <input wire:model.live='modify'
                                            type='checkbox' class="text-xs" />
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
                <div class=" col-12 col-sm-12 col-md-12  col-lg-8" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm  table-hover ">
                        <thead class="bg-sky h1">
                            <tr>
                                <th>Statement of Cash Flows</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            {{-- Header --}}
                            @foreach (CashFlowServices::GetHeaderList($LOCATION_ID) as $hlist)
                                <tr class="font-weight-bold">
                                    <td>{{ $hlist->NAME }}
                                        @if ($modify)
                                            <button wire:click='editHeader({{ $hlist->ID }})'
                                                class="btn btn-xs btn-success">
                                                <i class="fa fa-wrench" aria-hidden="true"></i>
                                            </button>
                                            <button class="btn btn-xs btn-primary"
                                                wire:click='clickDetails({{ $hlist->ID }})'>
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Header AMOUNT --}}
                                    </td>
                                </tr>
                                {{-- Details --}}
                                @foreach (CashFlowServices::GetDetailList($hlist->ID) as $dList)
                                    <tr class="font-weight-normal">
                                        <td class="px-2">{{ $dList->NAME }}
                                            @if ($modify)
                                                <button wire:click='editDetails({{ $dList->ID }})'
                                                    class="btn btn-xs btn-primary">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i>
                                                </button>
                                                <button class="btn btn-xs btn-warning"
                                                    wire:click='clickKey({{ $dList->ID }})'>
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @php
                                                $tempVar = CashFlowServices::getDetailsAmount(
                                                    $dList->ID,
                                                    $YEAR,
                                                    $LOCATION_ID,
                                                );
                                                $AMOUNT = $AMOUNT + $tempVar;
                                            @endphp {{-- Detail AMOUNT --}}
                                            @if ($dList->IS_TOTAL)
                                                {{ number_format($AMOUNT, 2) }}
                                            @else
                                                @if ($tempVar != 0)
                                                    {{ number_format($tempVar, 2) }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    {{-- key Account --}}
                                    @foreach (CashFlowServices::GetKeyList($dList->ID) as $keyList)
                                        <tr class="font-weight-light">
                                            <td class="px-4">
                                                {{ $keyList->NAME }}
                                                @if ($modify)
                                                    <button wire:click='editKey({{ $keyList->ID }})'
                                                        class="btn btn-xs btn-warning">
                                                        <i class="fa fa-wrench" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @php
                                                    $tempKey = CashFlowServices::getKeyAmount(
                                                        $keyList->ID,
                                                        $YEAR,
                                                        $LOCATION_ID,
                                                    );

                                                    $AMOUNT = $AMOUNT + $tempKey;
                                                @endphp
                                                @if ($tempKey != 0)
                                                    {{ number_format($tempKey, 2) }}
                                                @endif
                                                {{-- Key AMOUNT --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                            @if ($modify)
                                <tr>
                                    <td>
                                        <button wire:click='clickHeader()' class="btn btn-xs btn-success">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Header
                                        </button>
                                    </td>
                                    <td></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @livewire('CashFlow.KeyModal')
    @livewire('CashFlow.DetailsModal')
    @livewire('CashFlow.HeaderModal')
</div>
