<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientspayment') }}">Patient Payments </a></h5>
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
                                        <div class="col-md-8">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm">
                                                    <option value="0"> All Location</option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="mt-0">
                                                <label class="text-sm"><br /></label>

                                                <button class="btn btn-sm btn-primary w-100" wire:click='reloadList()'>
                                                    <i class="fa fa-refresh" aria-hidden="true"></i> Reload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1">No.</th>
                                        <th>Date</th>
                                        <th class="col-2">Patients</th>
                                        <th>Deposit</th>
                                        <th>Applied</th>
                                        <th>Balance</th>
                                        <th>Method</th>
                                        <th class="col-1">Ref No.</th>
                                        <th class="col-1">Ref Date.</th>
                                        <th>Confirm</th>
                                        <th class="col-1">Location</th>
                                        <th>Status</th>
                                        @can('patient.payment.create')
                                            <th class="text-center bg-success">
                                                <a href="{{ route('patientspayment_create') }}"
                                                    class="text-white btn btn-xs w-100">
                                                    <i class="fas fa-plus"></i> New
                                                </a>
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>
                                                <a href="{{ route('patientspayment_edit', ['id' => $list->ID]) }}"
                                                    class="text-primary"> {{ $list->CODE }} </a>
                                            </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->CONTACT_NAME }}</td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT, 2) }}</td>
                                            <td class="text-right"> {{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                                            <td class="text-right">
                                                {{ number_format($list->AMOUNT - $list->AMOUNT_APPLIED, 2) }}
                                            </td>
                                            <td> {{ $list->PAYMENT_METHOD }}</td>
                                            <td>{{ $list->RECEIPT_REF_NO }}</td>
                                            <td>{{ $list->RECEIPT_DATE ? date('m/d/Y', strtotime($list->RECEIPT_DATE)) : '' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($list->IS_CONFIRM)
                                                    <strong class="text-success">Yes</strong>
                                                @else
                                                    <strong class="text-danger">No</strong>
                                                @endif
                                            </td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td class="text-center"> {{ $list->STATUS }}</td>
                                            @can('patient.payment.create')
                                                <td class="text-center">
                                                    @can('patient.payment.print')
                                                        @if ($list->FILE_PATH)
                                                            <a href="{{ asset('storage/' . $list->FILE_PATH) }}"
                                                                target="_blank" class="btn-sm text-danger">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            </a>
                                                        @else
                                                            <a href="#" class="btn-sm text-secondary">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    <a href="{{ route('patientspayment_edit', ['id' => $list->ID]) }}"
                                                        class="btn-sm text-info">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    @can('patient.payment.update')
                                                        @if ($list->FILE_PATH)
                                                            <button wire:click='getConfirm({{ $list->ID }})' type="button"
                                                                wire:confirm="Are you sure this guaranteed letter is confirm?"
                                                                class="btn btn-outline-none btn-sm p-0  text-success">
                                                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-outline-none btn-sm p-0 text-secondary">
                                                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    @endcan

                                                    @can('patient.payment.delete')
                                                        <a href="#" wire:click='delete({{ $list->ID }})'
                                                            wire:confirm="Are you sure you want to delete this?"
                                                            class="btn-sm text-danger">
                                                            <i class="fas fa-times" aria-hidden="true"></i>
                                                        </a>
                                                    @endcan

                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{-- <div class="row">
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">
                                    <div class ="row text-xs">
                                        <div class="col-3 text-right">
                                            <label>Total Deposit :</label>
                                        </div>
                                        <div class="col-9 text-left text-primary h6">
                                            {{ number_format($TOTAL_DEPOSIT, 2) }}
                                        </div>
                                        <div class="col-3 text-right ">
                                            <label>Total Applied :</label>
                                        </div>
                                        <div class="col-9 text-left text-success h6">
                                            {{ number_format($TOTAL_APPLIED, 2) }}
                                        </div>

                                        <div class="col-3 text-right ">
                                            <label>Total Balance :</label>
                                        </div>
                                        <div class="col-9 text-left text-danger h6">
                                            {{ number_format($TOTAL_BALANCE, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
