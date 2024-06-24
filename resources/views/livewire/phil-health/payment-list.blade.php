<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-8">
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs">
                <tr class="bg-sky text-white">
                    <th class="col-1">No.</th>
                    <th class="col-4 text-left">Received Date</th>
                    <th class="col-2">Official Receipt No. </th>
                    <th class="col-2 text-left">Amount</th>
                    <th class="col-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-dark text-xs">
                @foreach ($paymentList as $list)
                    @php
                        $i++;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i }}</td>
                        <td class="text-center">
                            @if ($editId == $list->ID)
                                <input type="date" name="editReceivedDate" wire:model='editReceivedDate'
                                    class="form-control form-control-xs" />
                            @else
                                {{ \Carbon\Carbon::parse($list->RECEIVED_DATE)->format('m/d/Y') }}
                            @endif
                        </td>
                        <td class="text-left">
                            @if ($editId == $list->ID)
                                <input type="text" name="editRefNo" wire:model='editRefNo'
                                    class="form-control form-control-xs" />
                            @else
                                {{ $list->REF_NO }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($editId == $list->ID)
                                <input type="number" name="editAmount" wire:model='editAmount'
                                    class="form-control form-control-xs" />
                            @else
                                {{ number_format($list->AMOUNT) }}
                            @endif
                        </td>
                        <td class="text-center">
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

                        </td>
                    </tr>
                @endforeach
                <form id="quickForm" wire:submit.prevent='Store'>
                    <tr  class="text-xs">
                        <td></td>
                        <td>
                            <div class="mt-2">
                                <input type="date" name="RECEIVED_DATE" wire:model='RECEIVED_DATE'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <input type="text" name="REF_NO" wire:model='REF_NO'
                                    class="form-control form-control-sm text-xs" />
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <input type="number" name="AMOUNT" wire:model='AMOUNT'
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
                </form>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
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
