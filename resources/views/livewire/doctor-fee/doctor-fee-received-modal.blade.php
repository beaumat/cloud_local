<div>

    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> NEPHRO : {{ $DOCTOR_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th>Patient Name</th>
                                    <th class="col-1 ">SOA No.</th>
                                    <th class="col-1 ">Date Admiited</th>
                                    <th class="col-1 ">Date Discharged</th>
                                    <th class="col-2 text-center">No. of Treatment</th>
                                    <th class="col-1 text-right">Total</th>
                                    <th class="col-1"> Received Date</th>
                                    <th class="col-1">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">

                                @foreach ($dataList as $list)
                                    @php
                                        $TOTAL = $TOTAL + $list->AMOUNT ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $list->PATIENT_NAME }}</td>
                                        <td>{{ $list->CODE }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                        <td class="text-center">{{ $list->NO_TREAT }}</td>
                                        <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                        <td class="text-center">
                                            {{ $list->PF_RECEIVED_DATE ? date('m/d/Y', strtotime($list->PF_RECEIVED_DATE)) : '' }}
                                        </td>
                                        <td><button wire:click='received({{ $list->ID }})'
                                                class="btn btn-success btn-xs">Received</button>
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right h6 text-primary">Remaining:</td>
                                    <td class="text-right h6 text-primary">{{ number_format($TOTAL, 2) }}</td>
                                    <td class="text-center"> </td>
                                    <td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
