<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-2 bg-info">Item Description</th>
                <th class="col-1 bg-info">Qty </th>
                <th class="col-1 bg-info">Item Amount</th>
                <th class="col-1">Reference</th>
                <th class="col-1">Date</th>
                <th class="col-1">Applied</th>
                <th class="col-1">Running Bal.</th>
                <th class="col-1 text-center">GL Confirm</th>
                <th class="text-center col-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($data as $list)
                <tr>
                    <td>{{ $list->ITEM_NAME }}</td>
                    <td>{{ number_format($list->QUANTITY, 0) }}</td>
                    <td>{{ number_format($list->ITEM_AMOUNT, 2) }}</td>
                    <td>
                        <a target="_blank"
                            href="{{ route('patientspayment_edit', ['id' => $list->PATIENT_PAYMENT_ID]) }}">
                            {{ $list->CODE }}
                        </a>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }} </td>

                    <td class="text-right">{{ number_format($list->AMOUNT_APPLIED, 2) }}</td>
                    @php
                        $ORG_AMOUNT = $ORG_AMOUNT - $list->AMOUNT_APPLIED;
                    @endphp
                    <td class="text-right">{{ number_format($ORG_AMOUNT, 2) }}
                    </td>
                    <td class="text-center">
                        @if ($list->IS_CONFIRM)
                            <strong class="text-success">Yes</strong>
                        @else
                            <strong class="text-danger">No</strong>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($list->FILE_PATH)
                            <a href="{{ asset('storage/' . $list->FILE_PATH) }}" target="_blank"
                                class="btn-sm text-danger">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </a>
                        @else
                            <a href="#" class="btn-sm text-secondary">
                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                            </a>
                        @endif
                        <a href="#" title="Delete" id="deletebtn"
                            wire:click='delete({{ $list->ID }}, {{ $list->PATIENT_PAYMENT_ID }})'
                            wire:confirm="Are you sure you want to delete this?" class="btn-sm text-danger">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    @can('patient.payment.create')
        <a target="_BLANK" class="btn btn-success btn-xs" href="{{ route('patientspayment_create') }}"> <i
                class="fas fa-plus"></i> Create Payment</a>
    @endcan
</div>
