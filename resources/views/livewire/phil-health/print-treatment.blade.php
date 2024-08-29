<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="text-center">
                        <b class="print-address1 text-center">
                            {{ $REPORT_HEADER_1 }} <br />
                            {{ $REPORT_HEADER_2 }} <br />
                            {{ $REPORT_HEADER_3 }}</b>
                    </div>
                </div>
                <div class="col-12" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <div class="row">
                        <div class="col-12 text-center">
                            <b class="h4">HEMODIALYSIS TREATMENT SUMMARY</b>
                        </div>
                        <div class="col-12">
                            <div class="row mt-4">
                                <div class="col-3 text-right">NAME OF PATIENT : </div>
                                <div class="col-6 bottom-line"> {{ $PATIENT_NAME }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-right">CONFINEMENT PERIOD :</div>
                                <div class="col-6 bottom-line">
                                    {{ $DATE_ADMITTED ? date('m/d/Y', strtotime($DATE_ADMITTED)) . ' TO ' : '' }}
                                    {{ $DATE_DISCHARGED ? date('m/d/Y', strtotime($DATE_DISCHARGED)) : '' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-right">ATTENDING PHYSICIAN : </div>
                                <div class="col-6 bottom-line"> {{ $PHYSICIAN }} </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-right"> FIRST CASE RATE : </div>
                                <div class="col-6 bottom-line"> {{ $FIRST_CASE_RATE }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-right"> DIAGNOSIS : </div>
                                <div class="col-9 bottom-line"> {{ $FINAL_DIAGNOSIS }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                </div>
                <div class="col-3 text-center" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                </div>
                <div class="col-6 text-center mt-4 " @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <table class="table table-sm" border="1">
                        <thead>
                            <tr class="blackbox">
                                <td> <u class="h4">NO.</u></td>
                                <td class="left-line"> <u class="h4">DATE OF TREATMENT</u></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hemoList as $list)
                                @php
                                    $i++;
                                @endphp
                                <tr class="blackbox">
                                    <td><u class="h4"> {{ $i }}</u></td>
                                    <td class="left-line">
                                        <h4> {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}</h4>
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                if ($i == 0) {
                                    $i = 1;
                                }
                                if ($i < 0) {
                                    $i = 0;
                                }
                            @endphp
                            @for ($n = $i; $n < 15; $n++)
                                <tr class="blackbox">
                                    <td> <u class="h4">{{ $n + 1 }}</u> </td>
                                    <td class="left-line">
                                        <h4></h4>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="col-3 text-center">
                </div>
                <div class="col-12">
                    @if ($OUTPUT_SIGN)
                        <div class="row mt-4">
                            <div class="col-4">
                                Prepared by:
                                <div class="form-group row ">
                                    <div class="col-7 text-center"><strong class="bottom-line">
                                            {{ $USER_NAME }}</strong>
                                    </div>
                                    <div class="col-7 text-center"><i>PHIC IN-Charge</i></div>
                                </div>
                                <div class="form-group row  mt-4">
                                    <div class="col-12">Noted by:</div>
                                    <div class="col-7 text-center"><strong class="bottom-line">
                                            {{ $ADMINISTRATOR_NAME }}
                                        </strong>
                                    </div>
                                    <div class="col-7 text-center"><i>Administrator</i></div>
                                </div>
                            </div>
                            <div class="col-4">


                            </div>
                            <div class="col-4 text-right">
                                <div class="form-group row  mt-4">
                                    <div class="col-12 text-center bottom-line"><b>{{ $PATIENT_NAME }}</b></div>
                                    <div class="col-12 text-center"><i>Patient Signature Over Printed Name</i></div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

</div>
