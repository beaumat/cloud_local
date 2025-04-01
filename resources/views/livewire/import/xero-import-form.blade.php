<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('companygeneral_journal') }}"> Xero Import </a></h5>
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
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm">
                                                    <option value="0"></option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button class="btn btn-danger btn-xs mt-2"
                                                wire:click='generate()'>Generate</button>
                                        </div>
                                        <div class="col-md-3">

                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Year:</label>
                                                <select name="location" wire:model.live='year'
                                                    class="form-control form-control-sm">
                                                    <option value="0"></option>
                                                    @foreach ($yearList as $item)
                                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Month:</label>
                                                <select name="location" wire:model.live='month'
                                                    class="form-control form-control-sm">
                                                    <option value="0"></option>
                                                    @foreach ($monthList as $item)
                                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div style="max-height: 73vh; overflow-y: auto;" class="border">
                                    <div style="width:1500px;max-width:1900px;">
                                        <table class="table table-sm table-bordered table-hover">
                                            <thead class="text-xs bg-sky sticky-header">
                                                <tr>
                                                    <th>ACCOUNT</th>
                                                    <th>DATE</th>
                                                    <th>SOURCE TYPE</th>
                                                    <th class="col-3">DESCRIPTION</th>
                                                    <th class="text-center ">REFERENCE</th>
                                                    <th class="text-center ">DEBIT</th>
                                                    <th class="text-center ">CREDIT</th>
                                                    <th class="text-center ">BALANCE</th>
                                                    <th class="text-center ">GROSS</th>
                                                    <th class="text-center ">TAX</th>
                                                </tr>
                                            </thead>

                                            {{-- end of pending --}}
                                            <tbody class="text-xs">
                                                @foreach ($dataList as $list)
                                                    <tr>
                                                        <td>{{ $list->ACCOUNT }}</td>
                                                        <td>{{ $list->DATE }}</td>
                                                        <td>{{ $list->SOURCE_TYPE }}</td>
                                                        <td>{{ $list->DESCRIPTION }}</td>
                                                        <td>{{ $list->REFERENCE }}</td>
                                                        <td>{{ $list->DEBIT }}</td>
                                                        <td>{{ $list->CREDIT }}</td>
                                                        <td>{{ $list->BALANCE }}</td>
                                                        <td>{{ $list->GROSS }}</td>
                                                        <td>{{ $list->TAX }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
