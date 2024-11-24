
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
                                            <button class="btn btn-success btn-xs w-25"
                                                wire:click='export()'>Export</button>
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
                <div class=" col-12 col-sm-12 col-md-12  col-lg-6" style="max-height: 80vh; overflow-y: auto;">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <table class="table table-sm  table-hover ">
                        <thead class="bg-sky h1">
                            <tr>
                                <th>Account Name</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="h1">

                            @foreach ($dataList as $list)
                                <tr>

                                    <td
                                        class="@if ($list['ACCOUNT'] == $list['TYPE']) h4 text-primary text-left @endif @if ($list['TYPE'] == 'HEADER') text-info text-md mt-2 font-weight-bold @endif @if ($list['AMOUNT'] == '') pt-1 @endif @if ($list['ORDER'] == 'N') text-md text-primary @endif @if ($list['ORDER'] == 'S') text-md text-success @endif">
                                        {{ $list['ACCOUNT'] }}</td>
                                    <td
                                        class="@if ($list['ACCOUNT'] == $list['TYPE']) h4 text-primary @endif @if ($list['TYPE'] == 'HEADER') text-info text-md pb-2 font-weight-bold @endif  text-right @if ($list['ORDER'] == 'N') text-md text-primary @endif @if ($list['ORDER'] == 'S') text-md text-success @endif">
                                        @if ((float) $list['AMOUNT'] != 0)
                                            {{ number_format((float) $list['AMOUNT'], 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
