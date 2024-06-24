<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="text-center">
                        <b class="print-address1 text-center">RDL Building F. Torres Street, Davao City <br />Telephone
                            #:285-2403; Mobile #: 09258678600/9175041322 <br /> Email:
                            avidadavao.torres@yahoo.com.ph</b>

                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <b class="bottom-line">PHILHEALTH ACCREDITED:</b>
                            <div class="row mt-4">
                                <div class="col-3">Patient`s Name : </div>
                                <div class="col-9 bottom-line">
                                    <div class="row">
                                        <div class="col-10"> {{ $PATIENT_NAME }}</div>
                                        <div class="col-2 text-right">Age: {{ $AGE }}</div>
                                    </div>
                                </div>
                                <div class="col-3">Address : </div>
                                <div class="col-9 bottom-line"> {{ $ADDRESS }}</div>
                                <div class="col-3">Final Diagnosis : </div>
                                <div class="col-9 bottom-line"> {{ $FINAL_DIAGNOSIS }}</div>
                                <div class="col-3">Other Diagnosis : </div>
                                <div class="col-9 bottom-line"> {{ $OTHER_DIAGNOSIS }}</div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">
                                <div class="col-5"> Soa Reference No. : </div>
                                <div class="col-7 bottom-line"> {{ $CODE }}</div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-5">Date & Time Admitted:</div>
                                <div class="col-7 bottom-line">
                                    {{ \Carbon\Carbon::parse($DATE_ADMITTED)->format('m/d/Y') }}
                                    {{ \Carbon\Carbon::parse($TIME_ADMITTED)->format('h:i:s A') }}</div>
                                <div class="col-5">Date & Time Discharged:</div>
                                <div class="col-7 bottom-line">
                                    {{ \Carbon\Carbon::parse($DATE_DISCHARGED)->format('m/d/Y') }}
                                    {{ \Carbon\Carbon::parse($TIME_DISCHARGED)->format('h:i:s A') }}</div>
                                <div class="col-5">First Case Rate:</div>
                                <div class="col-7 bottom-line"> {{ $FIRST_CASE_RATE }}</div>
                                <div class="col-5">Second Case Rate:</div>
                                <div class="col-7 bottom-line"> {{ $SECOND_CASE_RATE }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <b class="text-lg">SUMMARY OF FEES</b>
                </div>
                <div class="col-12 mt-2" id="details">
                    <div class="row top-line right-line left-line">
                        <div class="col-4">
                        </div>
                        <div class="col-1 left-line">
                        </div>
                        <div class="col-4 text-center  left-line bottom-line">
                            AMOUNT OF DISCOUNTS
                        </div>
                        <div class="col-2  text-center left-line bottom-line">
                            PHILHEALTH BENEFITS
                        </div>
                        <div class="col-1  left-line">
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div class="col-4 text-center ">
                            PARTICULARS
                        </div>
                        <div class="col-1 text-center left-line">
                            ACTUAL <br /> CHARGES
                        </div>
                        <div class="col-1 text-center left-line">
                            VAT EXEMPT
                        </div>
                        <div class="col-1 text-center left-line">
                            SENIOR CITIZEN / PWD
                        </div>
                        <div class="col-1 text-center  left-line text-xs">
                            <div class="row text-left">
                                <div class="col-12">___PCSO</div>
                                <div class="col-12">___DSWD</div>
                                <div class="col-12">___DOH(MAP)</div>
                                <div class="col-12">___HMO</div>
                                <div class="col-12">___LINGAP</div>
                            </div>
                        </div>
                        <div class="col-1 text-center left-line">
                            AMOUNT AFTER DISCOUNT
                        </div>
                        <div class="col-1  left-line text-center ">
                            First <br /> Case Rate amount
                        </div>
                        <div class="col-1 left-line text-center ">
                            Second Case Rate amount
                        </div>
                        <div class="col-1 text-center left-line">
                            Ouf of Pocket of Patient
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-center ">
                            <b> HCI Fees</b>
                        </div>
                        <div id="p-charge" class="col-1 text-center left-line">
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line">
                        </div>
                        <div id="p-first" class="col-1  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Room and Board
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_ROOM_N_BOARD > 0)
                                {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_ROOM_N_BOARD > 0)
                                {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_ROOM_N_BOARD > 0)
                                {{ number_format($SP_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_ROOM_N_BOARD > 0)
                                {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center "> </div>
                        <div id="p-second" class="col-1 left-line text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line"> </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Drugs & Medicine
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_DRUG_N_MEDICINE > 0)
                                {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_DRUG_N_MEDICINE > 0)
                                {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_DRUG_N_MEDICINE > 0)
                                {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_DRUG_N_MEDICINE > 0)
                                {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Laboratory & Diagnostics
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                                {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Operating Room Fee
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_OPERATING_ROOM_FEE > 0)
                                {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_OPERATING_ROOM_FEE > 0)
                                {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_OPERATING_ROOM_FEE > 0)
                                {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_OPERATING_ROOM_FEE > 0)
                                {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Supplies
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_SUPPLIES > 0)
                                {{ number_format($CHARGES_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_SUPPLIES > 0)
                                {{ number_format($VAT_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_SUPPLIES > 0)
                                {{ number_format($SP_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_SUPPLIES > 0)
                                {{ number_format($GOV_SUPPLIES, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Others: Pls. specify
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">
                            @if ($CHARGES_OTHERS > 0)
                                {{ number_format($CHARGES_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">
                            @if ($VAT_OTHERS > 0)
                                {{ number_format($VAT_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                            @if ($SP_OTHERS > 0)
                                {{ number_format($SP_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                            @if ($GOV_OTHERS > 0)
                                {{ number_format($GOV_OTHERS, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line font-weight-bold">
                            @if ($CHARGES_SUB_TOTAL > 0)
                                {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line font-weight-bold">
                            @if ($VAT_SUB_TOTAL > 0)
                                {{ number_format($VAT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line font-weight-bold">
                            @if ($SP_SUB_TOTAL > 0)
                                {{ number_format($SP_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs font-weight-bold">
                            @if ($GOV_SUB_TOTAL > 0)
                                {{ number_format($GOV_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line font-weight-bold"> </div>
                        <div id="p-first" class="col-1  left-line text-center  font-weight-bold">
                            @if ($P1_SUB_TOTAL > 0)
                                {{ number_format($P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-center font-weight-bold ">
                            @if ($P2_SUB_TOTAL > 0)
                                {{ number_format($P2_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line font-weight-bold">
                            @if ($OP_SUB_TOTAL > 0)
                                {{ number_format($OP_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            Professional Fee/s
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">

                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">

                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    {{-- Doctor --}}

                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                {{ $i . '.)' }} {{ $list->NAME }}
                            </div>
                            <div id="p-charge" class="col-1 text-center  left-line">
                                @if ($list->AMOUNT > 0)
                                    {{ number_format($list->AMOUNT, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-center  left-line">

                            </div>
                            <div id="p-sp" class="col-1 text-center   left-line">

                            </div>
                            <div id="p-gov" class="col-1 text-center  left-line text-xs"> </div>
                            <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                            <div id="p-first" class="col-1  left-line text-center ">
                                @if ($list->AMOUNT > 0)
                                    {{ number_format($list->AMOUNT, 2) }}
                                @endif
                            </div>
                            <div id="p-second" class="col-1 left-line text-center ">

                            </div>
                            <div id="p-pocket" class="col-1 text-center left-line">

                            </div>
                        </div>
                    @endforeach
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line font-weight-bold">
                            @if ($PROFESSIONAL_FEE_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line font-weight-bold"> </div>
                        <div id="p-sp" class="col-1 text-center   left-line font-weight-bold"> </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs font-weight-bold"> </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line font-weight-bold" > </div>
                        <div id="p-first" class="col-1  left-line text-center font-weight-bold">
                            @if ($PROFESSIONAL_FEE_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-center font-weight-bold"> </div>
                        <div id="p-pocket" class="col-1 text-center left-line font-weight-bold"> </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">
                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            &nbsp;
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line">

                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line">

                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs">

                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center ">

                        </div>
                        <div id="p-second" class="col-1 left-line text-center ">

                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line">

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>TOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line font-weight-bold">
                            @if ($CHARGE_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-center  left-line font-weight-bold">
                            @if ($VAT_TOTAL > 0)
                                {{ number_format($VAT_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-center   left-line font-weight-bold">
                            @if ($SP_TOTAL > 0)
                                {{ number_format($SP_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs font-weight-bold">
                            @if ($GOV_TOTAL > 0)
                                {{ number_format($GOV_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line font-weight-bold"> </div>
                        <div id="p-first" class="col-1  left-line text-center font-weight-bold">
                            @if ($P1_TOTAL > 0)
                                {{ number_format($P1_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-center  font-weight-bold">
                            @if ($P2_TOTAL > 0)
                                {{ number_format($P2_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-center left-line font-weight-bold">
                            @if ($OP_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL - $P1_TOTAL, 2) }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="row">
                        <div class="col-4">
                            Prepared by:
                            <div class="form-group row  mt-4">
                                <div class="col-7 text-center"><strong class="bottom-line">
                                        {{ $USER_NAME }}</strong>
                                </div>
                                <div class="col-7 text-center">PHIC IN-Charge</div>
                                <div class="col-12 ">Date Signed: {{ $DATE_SIGNED }}</div>
                                <div class="col-12 ">CONTACT No. {{ $USER_CONTACT }}</div>
                            </div>
                        </div>
                        <div class="col-4"></div>
                        <div class="col-4">
                            Conforme:
                            <div class="form-group row  mt-4">
                                <div class="col-12 text-center bottom-line"><b>{{ $PATIENT_NAME }}</b></div>
                                <div class="col-12 ">Member/Patient/Authorized Representative</div>
                                <div class="col-12 ">(Signature over printed name)</div>
                                <div class="col-12 text-xs">Relationship of member of authorized representative</div>
                                <div class="col-12 bottom-line">&nbsp;</div>
                                <div class="col-12 ">Date Signed: {{ $DATE_SIGNED }}</div>
                                <div class="col-12 ">CONTACT No. {{ $PATIENT_CONTACT }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
