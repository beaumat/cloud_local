   <div class="col-12" id="details" @if ($PRE_SIGN_DATA) style="opacity: 0.0" @endif>
                    <div class="row top-line right-line left-line">
                        <div class="col-4">
                        </div>
                        <div class="col-1 left-line">
                        </div>
                        <div class="col-4 text-center  left-line bottom-line text-sm">
                            AMOUNT OF DISCOUNTS
                        </div>
                        <div class="col-2  text-center left-line bottom-line text-sm">
                            PHILHEALTH BENEFITS
                        </div>
                        <div class="col-1  left-line">
                        </div>
                    </div>

                    <div class="row bottom-line right-line left-line">
                        <div class="col-4 text-center text-md ">
                            PARTICULARS
                        </div>
                        <div class="col-1 text-center left-line ">
                            ACTUAL <br /> CHARGES
                        </div>
                        <div class="col-1 text-center left-line">
                            VAT EXEMPT
                        </div>
                        <div class="col-1 text-center left-line">
                            SENIOR CITIZEN / PWD
                        </div>
                        <div class="col-1 text-center  left-line">
                            <div class="row text-left text-xs">
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
                            Ouf of <br /> Pocket <br /> of Patient
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
                    @if ($CHARGES_ROOM_N_BOARD > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Room and Board
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($CHARGES_ROOM_N_BOARD > 0)
                                    {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_ROOM_N_BOARD > 0)
                                    {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_ROOM_N_BOARD > 0)
                                    {{ number_format($SP_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
                                @if ($GOV_ROOM_N_BOARD > 0)
                                    {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                                @endif
                            </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line"> </div>
                            <div id="p-first" class="col-1  left-line text-right "> </div>
                            <div id="p-second" class="col-1 left-line text-right "> </div>
                            <div id="p-pocket" class="col-1 text-center left-line"> </div>
                        </div>
                    @endif
                    @if ($CHARGES_DRUG_N_MEDICINE > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Drugs & Medicine
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($CHARGES_DRUG_N_MEDICINE > 0)
                                    {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_DRUG_N_MEDICINE > 0)
                                    {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_DRUG_N_MEDICINE > 0)
                                    {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
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
                    @endif
                    @if ($CHARGES_SUPPLIES > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Supplies
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($CHARGES_SUPPLIES > 0)
                                    {{ number_format($CHARGES_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_SUPPLIES > 0)
                                    {{ number_format($VAT_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_SUPPLIES > 0)
                                    {{ number_format($SP_SUPPLIES, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
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
                    @endif
                    @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Laboratory & Diagnostics
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_LAB_N_DIAGNOSTICS > 0)
                                    {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
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
                    @endif
                    @if ($CHARGES_OPERATING_ROOM_FEE > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Operating Room Fee
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                {{-- @if ($CHARGES_OPERATING_ROOM_FEE > 0) --}}
                                {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                                {{-- @endif --}}
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_OPERATING_ROOM_FEE > 0)
                                    {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_OPERATING_ROOM_FEE > 0)
                                    {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
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
                    @endif
                    @if ($CHARGES_OTHERS > 0)
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                Administrative & Other Fees
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($CHARGES_OTHERS > 0)
                                    {{ number_format($CHARGES_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right  left-line">
                                @if ($VAT_OTHERS > 0)
                                    {{ number_format($VAT_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-sp" class="col-1 text-right   left-line">
                                @if ($SP_OTHERS > 0)
                                    {{ number_format($SP_OTHERS, 2) }}
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs">
                                @if ($GOV_OTHERS > 0)
                                    {{ number_format($GOV_OTHERS, 2) }}
                                @endif
                            </div>

                            <div id="p-first" class="col-2  left-line text-center "> </div>
                            <div id="p-second" class="col-1 left-line text-center "> </div>
                            <div id="p-pocket" class="col-1 text-center left-line">
                                @if ($OP_OTHERS > 0)
                                    {{ number_format($OP_OTHERS, 2) }}
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold">
                            @if ($CHARGES_SUB_TOTAL > 0)
                                {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold">
                            @if ($VAT_SUB_TOTAL > 0)
                                {{ number_format($VAT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold">
                            @if ($SP_SUB_TOTAL > 0)
                                {{ number_format($SP_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line font-weight-bold">
                            @if ($GOV_SUB_TOTAL > 0)
                                {{ number_format($GOV_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line font-weight-bold">
                            @if ($AD_SUB_TOTAL > 0)
                                {{ number_format($AD_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line text-right  font-weight-bold">
                            @if ($P1_SUB_TOTAL > 0)
                                {{ number_format($P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right font-weight-bold ">
                            @if ($P2_SUB_TOTAL > 0)
                                {{ number_format($P2_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold">

                            {{ number_format($OP_SUB_TOTAL, 2) }}

                        </div>
                    </div>
                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left font-weight-light">
                            Professional Fee/s
                        </div>
                        <div id="p-charge" class="col-1 text-center  left-line"> </div>
                        <div id="p-vat" class="col-1 text-center  left-line"> </div>
                        <div id="p-sp" class="col-1 text-center   left-line"> </div>
                        <div id="p-gov" class="col-1 text-center  left-line text-xs"> </div>
                        <div id="p-after-disc" class="col-1 text-center  left-line"> </div>
                        <div id="p-first" class="col-1  left-line text-center "> </div>
                        <div id="p-second" class="col-1 left-line text-center "> </div>
                        <div id="p-pocket" class="col-1 text-center left-line"> </div>
                    </div>
                    {{-- Doctor --}}
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <div class="row bottom-line right-line left-line">
                            <div id="p-particular" class="col-4 text-left ">
                                {{ $i . '. ' }} <span class="text-sm">{{ $list->NAME }}</span>
                            </div>
                            <div id="p-charge" class="col-1 text-right  left-line">
                                @if ($list->AMOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-vat" class="col-1 text-right left-line"> </div>
                            <div id="p-sp" class="col-1 text-right left-line">
                                @if ($list->DISCOUNT > 0)
                                    <i>{{ number_format($list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-gov" class="col-1 text-right  left-line text-xs"> </div>
                            <div id="p-after-disc" class="col-1 text-right  left-line">
                                @if ($list->DISCOUNT > 0)
                                    <i> {{ number_format($list->AMOUNT - $list->DISCOUNT, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-first" class="col-1  left-line text-right">
                                @if ($list->FIRST_CASE > 0)
                                    <i> {{ number_format($list->FIRST_CASE, 2) }}</i>
                                @endif
                            </div>
                            <div id="p-second" class="col-1 left-line text-right "> </div>
                            <div id="p-pocket" class="col-1 text-right left-line">
                                @if ($list->FIRST_CASE > 0)
                                    <i>
                                        0.00
                                    </i>
                                @endif
                            </div>
                        </div>
                    @endforeach


                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>SUBTOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold">
                            @if ($PROFESSIONAL_FEE_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold"> </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold">
                            @if ($PROFESSIONAL_DISCOUNT_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_DISCOUNT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs font-weight-bold"> </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_FEE_SUB_TOTAL - $PROFESSIONAL_DISCOUNT_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line text-right font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                {{ number_format($PROFESSIONAL_P1_SUB_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right font-weight-bold"> </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold">
                            @if ($PROFESSIONAL_P1_SUB_TOTAL > 0)
                                0.00
                            @endif
                        </div>
                    </div>


                    <div class="row bottom-line right-line left-line">
                        <div id="p-particular" class="col-4 text-left ">
                            <b>TOTAL</b>
                        </div>
                        <div id="p-charge" class="col-1 text-right  left-line font-weight-bold">
                            @if ($CHARGE_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-vat" class="col-1 text-right  left-line font-weight-bold">
                            @if ($VAT_TOTAL > 0)
                                {{ number_format($VAT_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-sp" class="col-1 text-right   left-line font-weight-bold">
                            @if ($SP_TOTAL > 0)
                                {{ number_format($SP_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-gov" class="col-1 text-right  left-line text-xs font-weight-bold">
                            @if ($GOV_TOTAL > 0)
                                {{ number_format($GOV_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-after-disc" class="col-1 text-right  left-line font-weight-bold">
                            @if ($CHARGE_TOTAL > 0)
                                {{ number_format($CHARGE_TOTAL - $SP_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-first" class="col-1  left-line text-right font-weight-bold">
                            @if ($P1_TOTAL > 0)
                                {{ number_format($P1_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-second" class="col-1 left-line text-right  font-weight-bold">
                            @if ($P2_TOTAL > 0)
                                {{ number_format($P2_TOTAL, 2) }}
                            @endif
                        </div>
                        <div id="p-pocket" class="col-1 text-right left-line font-weight-bold">
                            {{ number_format($OP_TOTAL, 2) }}
                        </div>
                    </div>
                </div>