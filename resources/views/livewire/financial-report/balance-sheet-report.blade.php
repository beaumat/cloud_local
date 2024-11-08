@php
    use App\Services\NumberServices;
@endphp
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialbalance_sheet_report') }}"> Balance Sheet Statement </a>
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
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_FROM" titleName="Start Date "
                                            wire:model.live='DATE_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TO" titleName="End Date"
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
                                <th>Account Name</th>
                                <th class="text-right">Amount</th>

                            </tr>
                        </thead>
                        <tbody class="h1">

                            {{-- Asset --}}
                            @if (count($bankList) > 0 ||
                                    count($arList) > 0 ||
                                    count($currentAssetList) > 0 ||
                                    count($fixedAssetList) > 0 ||
                                    count($nonCurrentAssietList) > 0)
                                <tr>
                                    <td class='text-primary text-md'>Asset</td>
                                    <td></td>

                                </tr>
                                @if (count($bankList) > 0)
                                    @php
                                        $bankTotal = 0;
                                    @endphp
                                    <tr>
                                        <td class="px-3 text-info text-sm">Bank</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($bankList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $bankTotal = $bankTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Bank</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($bankTotal, 2) }}
                                            </div>


                                        </td>
                                    </tr>

                                @endif
                                @if (count($arList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Accounts Receivable</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($arList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $arTotal = $arTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Accounts Receivable</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($arTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>

                                @endif

                                @if (count($currentAssetList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Current Asset</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($currentAssetList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $currentAssetTotal = $currentAssetTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Current Asset</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($currentAssetTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>

                                @endif

                                @if (count($fixedAssetList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Fixed Asset</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($fixedAssetList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $fixedAssetTotal = $fixedAssetTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Fixed Asset</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($fixedAssetTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>

                                @endif
                                @if (count($nonCurrentAssietList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Non-Current Asset</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($nonCurrentAssietList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $nonCurrentAssietTotal = $nonCurrentAssietTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>

                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Non-Current Asset</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($nonCurrentAssietTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>

                                @endif
                                @php
                                    $assetTotal =
                                        $bankTotal +
                                        $arTotal +
                                        $currentAssetTotal +
                                        $fixedAssetTotal +
                                        $nonCurrentAssietTotal;
                                @endphp
                                <tr>
                                    <td class='text-primary text-md'>Total Asset</td>
                                    <td class="text-right">
                                        <div class="border-top border-primary text-primary text-md">
                                            {{ number_format($assetTotal, 2) }}
                                        </div>
                                    </td>

                                </tr>
                                {{-- end asset --}}
                            @endif


                            {{-- LIABILITY --}}
                            @if (count($apList) > 0 ||
                                    count($creditCardList) > 0 ||
                                    count($currentLiabilityList) > 0 ||
                                    count($nonCurrentLiabilityList) > 0)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class='text-primary text-md'>Liability</td>
                                    <td></td>
                                </tr>
                                @if (count($apList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Accounts Payable</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($apList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $apTotal = $apTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Accounts Payable</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($apTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif


                                @if (count($creditCardList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Credit Card</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($creditCardList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $creditCardTotal = $creditCardTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Credit Card</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($creditCardTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @if (count($currentLiabilityList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Current Liability</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($currentLiabilityList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $currentLiabilityTotal = $currentLiabilityTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Current Liability</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($currentLiabilityTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif


                                @if (count($nonCurrentLiabilityList) > 0)

                                    <tr>
                                        <td class="px-3 text-info text-sm">Non-Current Liability</td>
                                        <td></td>
                                    </tr>
                                    @foreach ($nonCurrentLiabilityList as $list)
                                        <tr>
                                            <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                            @php
                                                $amount = $list->AMOUNT ?? 0;
                                                $nonCurrentLiabilityTotal = $nonCurrentLiabilityTotal + $amount;
                                            @endphp
                                            <td class='text-right'>{{ number_format($amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4">Total Non-Current Liability</td>
                                        <td class="text-right">
                                            <div class="border-top border-secondary">
                                                {{ number_format($nonCurrentLiabilityTotal, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @php
                                    $liabilityTotal =
                                        $apTotal +
                                        $creditCardTotal +
                                        $currentLiabilityTotal +
                                        $nonCurrentLiabilityTotal;
                                @endphp
                                <tr>
                                    <td class='text-primary text-md'>Total Liability</td>
                                    <td class="text-right">
                                        <div class="border-top border-primary text-primary text-md">
                                            {{ number_format($liabilityTotal, 2) }}
                                        </div>
                                    </td>

                                </tr>
                                {{-- end asset --}}
                            @endif
                            @php
                                $netAsset = $assetTotal - $liabilityTotal;
                            @endphp
                            <tr>
                                <td class="px-1 text-md text-success">Net Asset</td>
                                <td class="text-right">
                                    <div class="border-top border-secondary text-success text-md">
                                        {{ number_format($netAsset, 2) }}
                                    </div>
                                </td>
                            </tr>

                            {{-- EQUITY --}}
                            @if (count($equityList) > 0 || $RetainingEarnings > 0 || $net_income > 0)                 
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class='text-primary text-md'>Equity</td>
                                    <td></td>
                                </tr>
                                @foreach ($equityList as $list)
                                    <tr>
                                        <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                        @php
                                            $amount = $list->AMOUNT ?? 0;
                                            $equityTotal = $equityTotal + $amount;
                                        @endphp
                                        <td class='text-right'>{{ number_format($amount, 2) }}</td>
                                    </tr>
                                @endforeach
                                {{-- @if ($CurrentYearEarnings > 0)
                                    <tr>
                                        <td class="px-4">Current Year Earnings</td>
                                        <td class='text-right'>{{ number_format($CurrentYearEarnings, 2) }}</td>
                                    </tr>
                                @endif --}}

                                @if ($RetainingEarnings > 0)
                                    <tr>
                                        <td class="px-4">Retaining Earnings (Previous)</td>
                                        <td class='text-right'>{{ number_format($RetainingEarnings, 2) }}</td>
                                    </tr>
                                @endif

                                @if ($net_income > 0)
                                    <tr>
                                        <td class="px-4">Net Income</td>
                                        <td class='text-right'>{{ number_format($net_income, 2) }}</td>
                                    </tr>
                                @endif

                                @php
                                    $equityTotal = $equityTotal + $net_income + $RetainingEarnings;
                                @endphp

                                <tr>
                                    <td class="px-1 text-md text-primary">Total Equity</td>
                                    <td class="text-right">
                                        <div class="border-top border-secondary text-primary text-md">
                                            {{ number_format($equityTotal, 2) }}
                                        </div>
                                    </td>
                                </tr>
                                {{-- end asset --}}
                            @endif

                            <tr>
                                <td class="text-md text-success">Total Liabilites & Equity</td>
                                <td class="text-right">
                                    <div class="border-top border-secondary text-success text-md">
                                        {{ number_format($liabilityTotal + $equityTotal, 2) }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
