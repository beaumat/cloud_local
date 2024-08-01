<div>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> NEPHRO : {{ $DOCTOR_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table @if ($isDisabled == true) style="opacity: 0.5;pointer-events: none;" @endif
                            class="table table-sm table-bordered table-hover">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th>PATIENT</th>
                                    <th class="col-1 ">DATE ADMITTED</th>
                                    <th class="col-1 ">DATE DISCHARGE</th>
                                    <th class="col-2 text-center">NO. OF TREATMENTS</th>
                                    <th class="col-1 text-right">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">

                                {{-- +"PATIENT_NAME": "AMOY, GORGONIA, T"
                                +"DATE_ADMITTED": "2024-07-18"
                                +"DATE_DISCHARGED": "2024-07-25"
                                +"NO_TREAT": "3"
                                +"TOTAL": "1050.0000"
                                +"PHIC_ID": 9 --}}


                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->PATIENT_NAME }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_ADMITTED)) }}</td>
                                        <td>{{ date('m/d/Y', strtotime($list->DATE_DISCHARGED)) }}</td>
                                        <td class="text-center">{{ $list->NO_TREAT }}</td>
                                        <td class="text-right">{{ number_format($list->TOTAL, 2) }}</td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" wire:click="save">Print</button>
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
