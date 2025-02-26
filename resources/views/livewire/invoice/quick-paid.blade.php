<div>
    <button wire:click="openModal" class="btn btn-warning btn-sm text-xs ">
        Quick Philhealth Paid
    </button>

    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Philhealth</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="modal-body">
                        <div class='row'>
                            <div class="col-4">
                                <input type="text" wire:model.live='search' placeholder="Search here..."
                                    class="form-control form-control-sm" />
                            </div>
                            <div class="col-4">
                            </div>
                            <div class="col-4">
                            </div>
                            <div class="col-12">
                                <div style="max-height: 73vh; overflow-y: auto;" class="border">
                                    <table class="table table-sm table-bordered table-hover mt-1">
                                        <thead class='bg-sky'>
                                            <th>Invoice No.</th>
                                            <th>Date</th>
                                            <th>LHIO No.</th>
                                            <th>Date Due</th>
                                            <th class="text-center">No. Treatment</th>
                                            <th>Admitted</th>
                                            <th>Discharged</th>
                                            <th>Patient</th>
                                            <th>Nephro</th>
                                            <th>Amount</th>
                                            <th>Balance</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataList as $list)
                                                <tr>
                                                    <td>{{ $list->CODE }}</td>
                                                    <td>{{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                                    <td>
                                                        <a href="#"
                                                            wire:click='openARform({{ $list->PHILHEALTH_ID }})'>{{ $list->PO_NUMBER }}</a>

                                                    </td>
                                                    <td>{{ date('m/d/Y', strtotime($list->DUE_DATE)) }}</td>
                                                    <td class="text-center">{{ $list->TOTAL_TREATMENT }}</td>
                                                    <td>{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                                    <td>{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                                    <td>{{ $list->CUSTOMER_NAME }}</td>
                                                    <td>{{ $list->DOCTOR_NAME }}</td>
                                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-xs btn-success w-100"
                                                            wire:click='makePaid({{ $list->ID }})'>Paid</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @livewire('Invoice.QuickPaidPanel')
        @livewire('PhilHealth.ArForm')
    @endif

</div>
