<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-body">
                        <div class="form-group">
                            <span class="text-xs">Pre-sign output</span>
                            <input type="checkbox" wire:model.live='BASE_PRESIGN' />
                        </div>
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
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            @if ($BASE_PRESIGN)
                                                <a type="button" target="_BLANK" title="Print Soa"
                                                    href="{{ route('patientsprintout_soa_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                                                    class=" btn  btn-xs btn-primary">
                                                    <i class="fa fa-print" aria-hidden="true"></i> SOA
                                                </a>
                                                <a type="button" target="_BLANK" title="Print Summary"
                                                    href="{{ route('patientsprintout_summary_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                                                    class=" btn  btn-xs btn-primary ">
                                                    <i class="fa fa-print" aria-hidden="true"></i> Summary
                                                </a>
                                            @else
                                                <a type="button" target="_BLANK" title="Print Soa"
                                                    href="{{ route('patientsprintout_soa', ['id' => $PHILHEALTH_ID]) }}"
                                                    class=" btn  btn-xs btn-primary">
                                                    <i class="fa fa-print" aria-hidden="true"></i> SOA
                                                </a>
                                                <a type="button" target="_BLANK" title="Print Summary"
                                                    href="{{ route('patientsprintout_summary', ['id' => $PHILHEALTH_ID]) }}"
                                                    class=" btn  btn-xs btn-primary ">
                                                    <i class="fa fa-print" aria-hidden="true"></i> Summary
                                                </a>
                                            @endif

                                        </div>
                                        <div class="col-md-6 text-center">
                                            @if ($BASE_PRESIGN)
                                                <a type="button" target="_BLANK" title="Print Philheath Form"
                                                    href="{{ route('patientsprintout_csf_temp_out', ['id' => $PHILHEALTH_ID]) }}"
                                                    class="btn btn-info btn-xs ">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    CSF
                                                </a>
                                            @else
                                                <a type="button" target="_BLANK" title="Print Philheath Form"
                                                    href="{{ route('patientsprintout_csf', ['id' => $PHILHEALTH_ID]) }}"
                                                    class="btn btn-info btn-xs ">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    CSF
                                                </a>
                                                <a type="button" target="_BLANK" title="Print Philheath Form"
                                                    href="{{ route('patientsprintout_cf4', ['id' => $PHILHEALTH_ID]) }}"
                                                    class="btn btn-info btn-xs">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    CF4
                                                </a>
                                                <a type="button" target="_BLANK" title="Print Philheath CF2 Form"
                                                    href="{{ route('patientsprintout_cf2', ['id' => $PHILHEALTH_ID]) }}"
                                                    class="btn btn-info btn-xs">
                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                    CF2
                                                </a>
                                            @endif


                                        </div>
                                    </div>



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
