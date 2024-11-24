@php
    use App\Services\NumberServices;
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsfinancialincome_statement_report') }}"> Profit and Loss Statement</a>
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
                <div class=" col-12 col-sm-12 col-md-12  col-lg-8" style="max-height: 80vh; overflow-y: auto;">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <table class="table table-sm table-bordered table-hover ">
                        <thead class="bg-sky h1">
                            <tr>
                                <th class=''>Account</th>
                                <th class="text-right">Amount</th>
                                <th class="text-right"> Total </th>
                            </tr>
                        </thead>
                        <tbody class="h1">
                            @foreach ($dataList as $list)
                                <tr >
                                    <td
                                        class="@if ($list['TYPE'] == 'H') text-sm text-info @endif @if ($list['TYPE'] == 'P') text-sm text-primary @endif">
                                        {{ $list['ACCOUNT'] }}</td>
                                    <td class="text-right">
                                  @if ((float) $list['AMOUNT'] != 0)
                                            {{ number_format((float) $list['AMOUNT'], 2) }}
                                        @endif
                                    
                                    </td>
                                    <td
                                        class="text-right @if ($list['TYPE'] == 'H') text-sm text-info @endif  @if ($list['TYPE'] == 'P') text-sm text-primary @endif @if($list['TYPE'] == '' && $list['ACCOUNT'] == '' && $list['AMOUNT'] == '') bg-secondary  @endif">
                                           @if ((float) $list['TOTAL'] != 0)
                                            {{ number_format((float) $list['TOTAL'], 2) }}
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
