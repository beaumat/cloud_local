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
                @if ($STATUS == $openStatus)
                    <th class="text-center col-1">
                        Action
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($invoiceList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    <td>{{ $list->CODE }}</td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td class="text-right">

                        @if ($editPaymentId === $list->ID)
                            <input wire:model='editAmountApplied' type="number" class="form-control form-control-sm" />
                        @else
                            {{ number_format($list->AMOUNT_APPLIED, 2) }}
                        @endif
                    </td>

                    <td class="text-center">
                        @if ($editPaymentId === $list->ID)
                            <button title="Update" id="updatebtn" wire:click="update()"
                                class="text-success btn btn-sm btn-link">
                                <i class="fas fa-check" aria-hidden="true"></i>
                            </button>
                            <button title="Cancel" id="cancelbtn" href="#" wire:click="cancel()"
                                class="text-warning btn btn-sm btn-link">
                                <i class="fas fa-ban" aria-hidden="true"></i>
                            </button>
                        @else
                            <button title="Edit" id="editbtn"
                                wire:click='edit( {{ $list->ID }}, {{ $list->INVOICE_ID }}, {{ $list->AMOUNT_APPLIED }})'
                                class="text-info btn btn-sm btn-link">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </button>
                            <button title="Delete" id="deletebtn"
                                wire:click='delete({{ $list->ID }}, {{ $list->INVOICE_ID }})'
                                wire:confirm="Are you sure you want to delete this?"
                                class="text-danger btn btn-sm btn-link">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </button>
                        @endif
                    </td>

                    </td>
                </tr>
            @endforeach


        </tbody>

    </table>
    @livewire('Payment.ServiceChargeModal', ['CUSTOMER_ID' => $CUSTOMER_ID, 'LOCATION_ID' => $LOCATION_ID, 'PAYMENT_ID' => $PAYMENT_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])

</div>
