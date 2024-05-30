<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Orig. Amount</th>
                <th class="col-1">Balance</th>
                <th class="col-1">Payment</th>
                @if ($STATUS == 0)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    <td>
                        <a target="_blank"
                            href="{{ route('vendorsbills_edit', ['id' => $list->BILL_ID]) }}">{{ $list->CODE }}</a>
                    </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td class="text-right">
                        @if ($editPaymentId === $list->ID)
                            <input wire:model='editAmountApplied' type="number" class="form-control form-control-sm" />
                        @else
                            {{ number_format($list->AMOUNT_PAID, 2) }}
                        @endif
                    </td>

                    @if ($STATUS == 0)
                        <td class="text-center">
                            @if ($editPaymentId === $list->ID)
                                <a href="#" title="Update" id="updatebtn" wire:click="update()"
                                    class="text-success btn-sm">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </a>
                                <a href="#" title="Cancel" id="cancelbtn" href="#" wire:click="cancel()"
                                    class="text-warning btn-sm ">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </a>
                            @else
                                <a href="#" title="Edit" id="editbtn"
                                    wire:click='edit( {{ $list->ID }}, {{ $list->BILL_ID }}, {{ $list->AMOUNT_PAID }})'
                                    class="text-info  btn-sm">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </a>
                                <a href="#" title="Delete" id="deletebtn"
                                    wire:click='delete({{ $list->ID }}, {{ $list->BILL_ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="text-danger btn-sm">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                </a>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($STATUS === 0)
        @livewire('BillPayments.BillModal', ['VENDOR_ID' => $VENDOR_ID, 'LOCATION_ID' => $LOCATION_ID, 'CHECK_ID' => $CHECK_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'STATUS' => $STATUS])
    @endif
</div>
