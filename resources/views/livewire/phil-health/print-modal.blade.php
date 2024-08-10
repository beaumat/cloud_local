<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-body">
                        <div class="form-group">
                            <table class="table table-sm table-bordered">
                                <thead class="text-xs">
                                    <tr class="bg-sky">
                                        <th class='col-4'>Print </th>
                                        <th class="col-8"></th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">

                                    <tr>
                                        <td>SOA No. :</td>
                                        <td>{{ $CODE }}</td>
                                    </tr>

                                    <tr>
                                        <td>Date Created :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Patient Name :</td>
                                        <td>{{ $PATIENT_NAME }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date Admitted :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE_ADMITTED)) }}</td>
                                    </tr>

                                    <tr>
                                        <td>Date Discharged :</td>
                                        <td>{{ date('m/d/Y', strtotime($DATE_DISCHARGED)) }}</td>
                                    </tr>

                                    <tr>
                                        <td>No of Treatment :</td>
                                        <td>{{ $HEMO_TOTAL }}</td>
                                    </tr>
                                    <tr>
                                        <td>First Case Amount: </td>
                                        <td>{{ number_format($FIRST_CASE, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Diagnosis :</td>
                                        <td>{{ $FINAL_DIAGNOSIS }}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-10 text-left">
                                    <a type="button" target="_BLANK" title="Print Soa"
                                        href="{{ route('patientsphic_print', ['id' => $PHILHEALTH_ID]) }}"
                                        class=" btn  btn-sm btn-primary">
                                        <i class="fa fa-print" aria-hidden="true"></i> SOA & Treatment
                                    </a>
                                    <a type="button" target="_BLANK" title="Print Philheath Form"
                                        href="{{ route('patientsphic_print_form', ['id' => $PHILHEALTH_ID]) }}"
                                        class="btn btn-info btn-sm"> <i class="fa fa-print" aria-hidden="true"></i>
                                        CSF & CF4
                                    </a>
                                </div>
                                <div class="col-2 text-right">

                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
