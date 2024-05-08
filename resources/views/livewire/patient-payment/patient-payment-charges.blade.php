<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Date</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Orig. Amount</th>
                <th class="col-1">Balance</th>
                <th class="col-2 bg-info">Item Description</th>
                <th class="col-1 bg-info text-right"> Qty</th>
                <th class="col-1 bg-info"> UOM</th>
                <th class="col-1 bg-info text-right"> Item Amount</th>
                <th class="col-1 bg-warning text-right" >Paid</th>
                <th class="text-center col-1">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</td>
                    <td>
                        <a target="_blank"
                            href="{{ route('patientsservice_charges_edit', ['id' => $list->SERVICE_CHARGES_ID]) }}">{{ $list->CODE }}</a>
                    </td>
                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                    <td class="text-right">{{ number_format($list->BALANCE_DUE, 2) }}</td>
                    <td>{{ $list->ITEM_NAME }}</td>
                    <td class="text-right">{{ number_format($list->QUANTITY, 2) }}</td>
                    <td>{{ $list->SYMBOL }}</td>
                    <td class="text-right">{{ number_format($list->ITEM_AMOUNT, 2) }}</td>
                    <td class="text-right">
                        @if ($editPaymentChargeId === $list->ID)
                            <input wire:model='editAmountApplied' type="number" class="form-control form-control-sm" />
                        @else
                            {{ number_format($list->AMOUNT_APPLIED, 2) }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($editPaymentChargeId === $list->ID)
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
                                wire:click='edit( {{ $list->ID }}, {{ $list->SERVICE_CHARGES_ITEM_ID }}, {{ $list->AMOUNT_APPLIED }})'
                                class="text-info  btn-sm">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>
                            <a href="#" title="Delete" id="deletebtn"
                                wire:click='delete({{ $list->ID }},{{ $list->SERVICE_CHARGES_ITEM_ID }})'
                                wire:confirm="Are you sure you want to delete this?" class="text-danger btn-sm">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @livewire('PatientPayment.ServiceChargeList', ['PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'PATIENT_PAYMENT_ID' => $PATIENT_PAYMENT_ID, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED])
</div>
