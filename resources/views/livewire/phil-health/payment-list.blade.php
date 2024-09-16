<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs text-left">
                <tr class="bg-sky text-white">
                    <th class="col-1">Payment No.</th>
                    <th class="col-1">Date Created</th>
                    <th class="col-1">O.R No.</th>
                    <th class="col-1">O.R Date</th>
                    <th class="col-1">Gross Income</th>
                    <th class="col-1">WTax</th>
                    <th class="col-1">Less Amount</th>
                    <th class="col-1">Applied</th>
                    <th class="col-5">Notes</th>

                </tr>
            </thead>
            <tbody class="text-dark text-xs text-left">
                @foreach ($paymentList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>

                        <td>
                            <a target="_BLANK" href="{{ route('patientsphic_pay_edit', ['id' => $list->ID]) }}"
                                class="text-primary">
                                {{ $list->CODE }}
                            </a>
                        </td>
                        <td> {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                        <td>
                            @if ($editId == $list->ID)
                                <input type="text" name="editReceiptRefNo" wire:model='editReceiptRefNo'
                                    class="w-100 text-xs" />
                            @else
                                {{ $list->RECEIPT_REF_NO }}
                            @endif
                        </td>
                        <td>
                            @if ($editId == $list->ID)
                                <input type="date" name="editReceiptDate" wire:model='editReceiptDate'
                                    class="w-100 text-xs" />
                            @else
                                {{ \Carbon\Carbon::parse($list->RECEIPT_DATE)->format('m/d/Y') }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($editId == $list->ID)
                                <input type="number" name="editAmount" wire:model='editAmount' class="w-100 text-xs" />
                            @else
                                {{ number_format($list->AMOUNT, 2) }}
                            @endif
                        </td>
                        <td class="text-right">
                            {{ number_format($list->WTAX_AMOUNT, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($list->LESS_AMOUNT, 2) }}
                        </td>
                        <td class="text-right">
                            {{ number_format($list->AMOUNT_APPLIED, 2) }}
                        </td>
                        <td class="text-left">
                            @if ($editId == $list->ID)
                                <input type="text" name="editNotes" wire:model='editNotes' class="w-100 text-xs" />
                            @else
                                {{ $list->NOTES }}
                            @endif
                        </td>

                        {{-- <td class="text-center">
                            @if ($editId === $list->ID)
                                <button type="button" title="Update" id="updatebtn" wire:click="Update()"
                                    class="text-success btn btn-xs btn-link">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button type="button" title="Cancel" id="cancelbtn" href="#"
                                    wire:click="Cancel()" class="text-warning btn btn-xs btn-link">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button type="button" title="Payment" id="paybtn"
                                    wire:click='OpenPayShow({{ $list->ID }})'
                                    class="text-primary btn btn-xs btn-link">
                                    <i class="fa fa-paypal" aria-hidden="true"></i>
                                </button>
                                @if ($list->AMOUNT_APPLIED == 0)
                                    <button type="button" title="Edit" id="editbtn"
                                        wire:click='Edit({{ $list->ID }})' class="text-info btn btn-xs btn-link">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </button>

                                    <button type="button" title="Delete" id="deletebtn"
                                        wire:click='Delete({{ $list->ID }})'
                                        wire:confirm="Are you sure you want to delete this?"
                                        class="text-danger btn btn-xs btn-link">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </button>
                                @endif
                            @endif

                        </td> --}}
                    </tr>
                @endforeach
                {{-- <form id="quickForm" wire:submit.prevent='Store'>
                    <tr class="text-xs">
                        <td></td>
                        <td></td>

                        <td>
                            <div class="mt-2">
                                <input type="text" name="RECEIPT_REF_NO" wire:model='RECEIPT_REF_NO'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <input type="date" name="RECEIPT_DATE" wire:model='RECEIPT_DATE'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <input type="number" name="AMOUNT" wire:model='AMOUNT'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td></td>
                        <td>
                            <div class="mt-2">
                                <input type="text" name="NOTES" wire:model='NOTES'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <button type="submit" wire:loading.attr='hidden'
                                    @if ($PHILHEALTH_ID == 0) disabled @endif
                                    class="text-white btn bg-success btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>

                    </tr>
                </form> --}}
            </tbody>

        </table>
    </div>
    <div class="col-md-6">
        <a class="btn btn-xs btn-success" target="_BLANK" href="{{ route('patientsphic_pay_create') }}">Create Payment</a>
        {{-- @livewire('PhilHealth.ServicesChargesModal', ['PHILHEALTH_ID' => $PHILHEALTH_ID]) --}}
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-8 text-right">
                <label>First Case Rate Amount :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-info">{{ number_format($P1_TOTAL) }}</label>
            </div>
            <div class="col-md-8 text-right">
                <label>Total Collection :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-success">{{ number_format($PAYMENT_AMOUNT) }}</label>
            </div>
            <div class="col-md-8 text-right">
                <label>Total Balance :</label>
            </div>
            <div class="col-md-4 ">
                <label class="text-danger">{{ number_format($BALANCE) }}</label>
            </div>
        </div>
    </div>
</div>
