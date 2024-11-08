@php
    use App\Services\NumberServices;
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialincome_statement_report') }}"> Income Statement Report </a>
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
                    <table class="table table-sm table-bordered table-hover ">
                        <thead class="bg-sky h1">
                            <tr>
                                <th>Account Name</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right"> Total </th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @if (count($incomeList) > 0)
                                <tr>
                                    <td class="text-primary">Revenue</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $sub_total = 0;
                                @endphp
                                @foreach ($incomeList as $list)
                                    <tr>
                                        <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                        <td class="text-right">
                                            @php
                                                $amount = $list->AMOUNT;
                                                $sub_total = $sub_total + $amount;
                                            @endphp
                                            {{ NumberServices::AcctFormat($amount) }}
                                        </td>
                                        <td></td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-primary px-2">Total Revenue</td>
                                    <td>
                                        <div class="border-top border-secondary">
                                        </div>
                                    </td>
                                    <td class="text-right text-primary">
                                        {{ NumberServices::AcctFormat($sub_total) }}
                                        @php
                                            $total_income = $sub_total;
                                        @endphp
                                    </td>
                                </tr>
                                {{-- END OF REVENUE --}}
                            @endif
                            @if (count($cogsList) > 0)
                                <tr>
                                    <td class="text-primary">Cost of Sales</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $sub_total = 0;
                                @endphp
                                @foreach ($cogsList as $list)
                                    <tr>
                                        <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                        <td class="text-right">
                                            @php
                                                $amount = $list->AMOUNT;
                                                $sub_total = $sub_total + $amount;
                                            @endphp
                                            {{ NumberServices::AcctFormat($amount) }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-primary px-2">Total Cost of Sales</td>
                                    <td>
                                        <div class="border-top border-secondary">
                                        </div>
                                    </td>
                                    <td class="text-right text-primary">
                                        {{ NumberServices::AcctFormat($sub_total) }}
                                        @php
                                            $total_cogs = $sub_total;
                                        @endphp
                                    </td>
                                </tr>
                                {{-- end of cogs --}}
                            @endif
                            @php
                                $gross_profit = 0;
                            @endphp

                            <tr>
                                <td class="text-sm text-danger">Gross Profit</td>
                                <td></td>
                                <td class="text-right text-info text-sm text-danger">
                                    <div class="border-top border-secondary">
                                        @php
                                            $gross_profit = $total_income - $total_cogs;
                                        @endphp
                                        {{ NumberServices::AcctFormat($gross_profit) }}
                                    </div>
                                </td>
                            </tr>
                            {{-- end of Gross Profit --}}

                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="text-primary">Operating Expenses</td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $sub_total = 0;
                            @endphp
                            @foreach ($expensesList as $list)
                                <tr>
                                    <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                    <td class="text-right">
                                        @php
                                            $amount = $list->AMOUNT;
                                            $sub_total = $sub_total + $amount;
                                        @endphp
                                        {{ NumberServices::AcctFormat($amount) }}
                                    </td>

                                    <td></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-primary px-2">Total Operating Expenses</td>
                                <td>
                                    <div class="border-top border-secondary">
                                    </div>
                                </td>
                                <td class="text-right text-primary">
                                    {{ NumberServices::AcctFormat($sub_total) }}
                                    @php
                                        $total_expenses = $sub_total;
                                    @endphp
                                </td>
                            </tr>

                            @php
                                $operating_income = $gross_profit - $total_expenses;

                            @endphp
                            @if ($total_expenses > 0)
                                <tr>
                                    <td class="text-sm text-danger">Operating Income</td>
                                    <td></td>
                                    <td class="text-right text-info text-sm text-danger">
                                        <div class="border-top border-secondary">

                                            {{ NumberServices::AcctFormat($operating_income) }}

                                        </div>
                                    </td>
                                </tr>
                            @endif

                            @if (count($otherIncomeList) > 0 || count($otherExpensesList) > 0)
                                <tr>
                                    <td>Other Income and Expenses</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif

                            @if (count($otherIncomeList) > 0)
                                <tr>
                                    <td class="text-primary px-2">Other Income</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $sub_total = 0;
                                @endphp
                                @foreach ($otherIncomeList as $list)
                                    <tr>
                                        <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                        <td class="text-right">
                                            @php
                                                $amount = $list->AMOUNT;
                                                $sub_total = $sub_total + $amount;
                                            @endphp
                                            {{ NumberServices::AcctFormat($amount) }}
                                        </td>
                                    <tr></tr>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td class="text-info px-4">Total Other Income</td>
                                    <td>
                                        <div class="border-top border-secondary">
                                        </div>
                                    </td>
                                    <td class="text-right text-info">
                                        {{ NumberServices::AcctFormat($sub_total) }}
                                        @php
                                            $total_other_income = $sub_total;
                                        @endphp

                                    </td>

                                </tr>
                            @endif

                            @if (count($otherExpensesList) > 0)
                                <tr>
                                    <td class="text-primary px-2">Other Expenses</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @php
                                    $sub_total = 0;
                                @endphp

                                @foreach ($otherExpensesList as $list)
                                    <tr>
                                        <td class="px-4">{{ $list->ACCOUNT_TITLE }}</td>
                                        <td class="text-right">
                                            @php
                                                $amount = $list->AMOUNT;
                                                $sub_total = $sub_total + $amount;
                                            @endphp
                                            {{ NumberServices::AcctFormat($amount) }}
                                        </td>
                                    <tr></tr>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-info px-4">Total Other Expenses</td>
                                    <td>
                                        <div class="border-top border-secondary">
                                        </div>
                                    </td>
                                    <td class="text-right text-info">
                                        {{ NumberServices::AcctFormat($sub_total) }}
                                        @php
                                            $total_other_expenses = $sub_total;
                                        @endphp
                                    </td>

                                </tr>
                            @endif
                            @php
                                $net_other_income = $total_other_income - $total_other_expenses;
                            @endphp

                            @if ($net_other_income > 0)
                                <tr>
                                    <td>Net Other Income/Expenses</td>
                                    <td></td>
                                    <td class="text-right text-info text-sm text-danger">
                                        <div class="border-top border-secondary">
                                            {{ NumberServices::AcctFormat($net_other_income) }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            {{-- Net Income=Operating Income+Net Other Income --}}
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                            @php
                                $net_income = $operating_income + $net_other_income;
                            @endphp

                            @if ($operating_income + $net_other_income > 0)
                                <tr>
                                    <td class="text-info text-sm">Net Income</td>
                                    <td></td>
                                    <td class="text-right text-info text-sm">
                                        <div class="border-top border-secondary">

                                            {{ NumberServices::AcctFormat($net_income) }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
