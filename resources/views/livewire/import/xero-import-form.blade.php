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
                                        <div class="col-md-6">
                                            <div class="mt-0">
                                                <label class="text-sm">Excel File:</label>
                                                <div class="input-group input-group-xs">
                                                    <div class="custom-file text-xs">
                                                        <input type="file" class="custom-file-input text-xs"
                                                            id="fileUpload" wire:model='file'>
                                                        <label class="custom-file-label text-xs" for="fileUpload">
                                                            @if ($file)
                                                                {{ $file->getClientOriginalName() }}
                                                            @else
                                                                Choose file
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-4">
                                                <button type="button" class="mt-1 btn btn-sm btn-success"
                                                   wire:loading.attr='hidden' wire:click='import()'> Upload</button>

                                                <div wire:loading.delay>
                                                    <span class='spinner'></span>
                                                </div>
                                            </div>
                                        </div>
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>Date</th>
                                        <th>Source</th>
                                        <th>Description</th>
                                        <th>Reference</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                        <th>Gross</th>
                                        <th>Tax</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">

                                </tbody>
                            </table> --}}


                            @if ($data)
                                <h3>Imported Data:</h3>
                                <table border="1">
                                    <tr>
                                        @foreach ($data[0] as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                    @foreach ($data as $index => $row)
                                        @if ($index > 0)
                                            <tr>
                                                @foreach ($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
