<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th></th>
                <th class="col-1">Reference</th>
                <th class="col-1">Date</th>
                <th class="col-2">Amount Deposit</th>
                <th class="col-3">Payment Methods</th>
                <th class="col-2">Remaining</th>
                <th class="col-2">Amount Applied</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($data as $list)
                <tr>
                    <td class="text-center">
                        <input class="text-lg mt-2" type="checkbox"
                            wire:model.live="paymentSelected.{{ $list->ID }}" />
                    </td>
                    <td>
                        <label class="mt-2" for=""> {{ $list->CODE }}</label>
                    </td>
                    <td>
                        <label class="mt-2" for="">
                            {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</label>
                    </td>
                    <td class="text-right">
                        <label class="mt-2" for=""> {{ number_format($list->AMOUNT, 2) }}</label>
                    </td>
                    <td>
                        <label class="mt-2" for=""> {{ $list->PAYMENT_METHOD }}</label>
                    </td>
                    <td class="text-right">
                        <label class="mt-2"> {{ number_format($list->AMOUNT - $list->AMOUNT_APPLIED, 2) }}</label>
                    </td>
                    <th>
                        <input type="number" wire:model="paymentAmounts.{{ $list->ID }}"
                            class="form-control form-control-sm text-xs w-100" />
                    </th>
                </tr>
            @endforeach
        </tbody>
    </table>
    <button class="btn btn-info btn-sm" wire:click='save'> <i class="fas fa-plus"></i> Add </button>
</div>
