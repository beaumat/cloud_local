<div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-2">
                    @livewire('GenerateQRCode', ['code' => $CODE])
                </div>
                <div class="col-8 text-center mb-4">
                    <img class="print-logo" src="{{ asset('dist/logo/vida_logo.png') }}" />
                    <div class="print-address">
                        <b>{{ $REPORT_HEADER_1 }}</b>
                    </div>
                </div>
                <div class="col-2">

                </div>
                <div class="col-12 text-center">
                    <b class="print-title">HEMODIALYSIS TREATMENT SHEET</b>
                </div>
                <div class="col-6">
                    <b>ID NO.:</b> <u class="text-primary font-weight-bold">{{ $CODE }}</u>
                </div>

                <div class="col-6 text-right">
                    <b>PHIC NO.:</b> <u class="text-primary font-weight-bold">
                        @if ($PHIC_NO)
                            {{ $PHIC_NO }}
                        @else
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        @endif
                    </u>
                </div>
                <div class="col-12 top-line left-line right-line">
                    <div class="row font-weight-bold" id="firstfloor">
                        <div class="col-4">NAME:
                            <label class="text-primary font-weight-bold text-uppercase">{{ $FULL_NAME }}</label>
                        </div>
                        <div class="col-1">AGE:
                            <label class="text-primary font-weight-bold text-uppercase">{{ $AGE }}</label>
                        </div>
                        <div class="col-3">NO. OF TREATMENT: <label
                                class="text-primary font-weight-bold">{{ $NO_OF_TREATMENT }}</label></div>
                        <div class="col-2">MACHINE NO. :</div>
                        <div class="col-2">DATE: <label
                                class="text-primary font-weight-bold">{{ \Carbon\Carbon::parse($DATE)->format('m/d/Y') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 left-line bottom-line top-line">
                    <div class="row">
                        <div class="col-2" id="empty">
                            <div class="row text-center font-weight-bold">
                                <div class="col-12 bottom-line-hide">
                                    <p class="up-space down-space"> &nbsp; </p>
                                </div>
                                <div class="col-12 bottom-line up-space">
                                    &nbsp;
                                </div>
                                <div class="col-12 bottom-line up-space">
                                    WEIGHT
                                </div>
                                <div class="col-12 bottom-line">
                                    BLOOD PRESSURE
                                </div>
                                <div class="col-12 bottom-line">
                                    HEART RATE
                                </div>
                                <div class="col-12 bottom-line">
                                    O2 SATURATION
                                </div>
                                <div class="col-12 bottom-line">
                                    TEMPERATURE
                                </div>
                            </div>
                        </div>
                        <div class="col-2 left-line">
                            <div class="row">
                                <div class="col-12 bottom-line text-center">
                                    <p class="up-space down-space font-weight-bold"> LAST TREATMENT</p>
                                </div>
                                <div class="col-12 bottom-line">
                                    <div class="row text-center up-space font-weight-bold">
                                        <div class="col-6">
                                            PRE
                                        </div>
                                        <div class="col-6">
                                            POST
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center up-space">
                                        <div class="col-6 ">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_PRE_WEIGHT }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_POST_WEIGHT }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">
                                                &nbsp; {{ $OLD_PRE_BLOOD_PRESSURE }}/{{ $OLD_PRE_BLOOD_PRESSURE2 }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">
                                                &nbsp;{{ $OLD_POST_BLOOD_PRESSURE }}/{{ $OLD_POST_BLOOD_PRESSURE2 }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_PRE_HEART_RATE }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_POST_HEART_RATE }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_PRE_O2_SATURATION }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_POST_O2_SATURATION }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_PRE_TEMPERATURE }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $OLD_POST_TEMPERATURE }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 left-line">
                            <div class="row">
                                <div class="col-12 bottom-line text-center">
                                    <p class="up-space down-space font-weight-bold"> TODAYS TREATMENT </p>
                                </div>
                                <div class="col-12 bottom-line">
                                    <div class="row text-center up-space font-weight-bold">
                                        <div class="col-6">
                                            PRE
                                        </div>
                                        <div class="col-6">
                                            POST
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center up-space">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $PRE_WEIGHT }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $POST_WEIGHT }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">
                                                &nbsp;{{ $PRE_BLOOD_PRESSURE }}/{{ $PRE_BLOOD_PRESSURE2 }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">
                                                &nbsp;{{ $POST_BLOOD_PRESSURE }}/{{ $POST_BLOOD_PRESSURE2 }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $PRE_HEART_RATE }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $POST_HEART_RATE }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 bottom-line-hide">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $PRE_O2_SATURATION }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $POST_O2_SATURATION }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 ">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $PRE_TEMPERATURE }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="bottom-line2">&nbsp;{{ $POST_TEMPERATURE }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-6 left-line right-line ">
                            <div class="row">
                                <div class="col-12 bottom-line">
                                    <p class="up-space down-space"> <b>UF GOAL :</b><span
                                            class="text-sm">&nbsp;{{ $UF_GOAL }}</span></p>
                                </div>
                                <div class="col-4 ">
                                    <div class="row pb-2 font-weight-bold">
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row up-space right-space">
                                                <div class="col-7">
                                                    BFR
                                                </div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    &nbsp;{{ $BFR > 0 ? $BFR : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row right-space">
                                                <div class="col-7">DFR</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    &nbsp;{{ $DFR > 0 ? $DFR : '' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row right-space">
                                                <div class="col-7">DURATION</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    &nbsp;{{ $DURATION > 0 ? $DURATION . 'hrs' : '' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row right-space">
                                                <div class="col-7">DIALYZER</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    <span class="text-xs">&nbsp;{{ $DIALYZER }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row right-space">
                                                <div class="col-7">RE-USE NO.</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    <span class="text-md"> &nbsp;{{ $REUSE_NO }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 bottom-line-hide">
                                            <div class="row right-space">
                                                <div class="col-7">HEPARIN</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    <span class="text-xs"
                                                        style="position:absolute;width:80px;left:0">&nbsp;
                                                        {{ $HEPARIN }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12  ">
                                            <div class="row right-space">
                                                <div class="col-7">FLUSHING</div>
                                                <div
                                                    class="col-5 text-center bottom-line2 text-primary font-weight-normal">
                                                    <span class="text-xs"
                                                        style="position:absolute;width:80px;left:0">&nbsp;
                                                        {{ $FLUSHING }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 left-line">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <label class="up-space">SAFETY CHECK</label>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs">
                                                @if ($SC_MACHINE_TEST)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif MACHINE TEST
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs">
                                                @if ($SC_SECURED_CONNECTIONS)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif SECURED CONNECTIONS
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="mt-1"
                                                style="font-size: 11px; margin-left:5px; width:200px;">
                                                @if ($SC_SALINE_LINE_DOUBLE_CLAMP)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif SALINE LINE
                                                DOUBLE CLAMP
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs mt-4 row">
                                                <div class='col-6'>
                                                    CONDUCTIVITY:
                                                </div>
                                                <div class="col-6 bottom-line2">
                                                    &nbsp;{{ $SC_CONDUCTIVITY }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs mt-2 row">
                                                <div class='col-7'>
                                                    DIALYSATE&nbsp;TEMP:
                                                </div>
                                                <div class="col-5 bottom-line2">
                                                    &nbsp;{{ $SC_DIALYSATE_TEMP }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="text-xs" style="width:300px;"><b>RESIDUAL TEST:@if ($SC_RESIDUAL_TEST_NEGATIVE)
                                                    [&#10003;]@else[&nbsp;&nbsp;]
                                                    @endif NEGATIVE</b></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 left-line">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <label class="up-space">DIALYSATE BATH</label>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs">
                                                @if ($DB_STANDARD_HCOA)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif STANDARD HCOA
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs">
                                                @if ($DB_ACID)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif ACID
                                            </div>
                                        </div>

                                        <div class="col-12 text-center">
                                            <div class="row text-xs mt-3">
                                                <div class="col-3 text-right"> Na :</div>
                                                <div class="col-3 bottom-line2 text-primary">&nbsp;{{ $DIALSATE_N }}
                                                </div>
                                                <div class="col-3">meq/L </div>
                                            </div>

                                            <div class="row text-xs ">
                                                <div class="col-3 text-right"> K+ :</div>
                                                <div class="col-3 bottom-line2 text-primary">&nbsp;{{ $DIALSATE_K }}
                                                </div>
                                                <div class="col-3">meq/L </div>
                                            </div>

                                            <div class="row text-xs ">
                                                <div class="col-3 text-right"> Ca+ :</div>
                                                <div class="col-3 bottom-line2 text-primary">&nbsp;{{ $DIALSATE_C }}
                                                </div>
                                                <div class="col-3">meq/L </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 left-line bottom-line ">
                    <div class="row">
                        <div class="col-4 ">
                            <label class="text-sm">NEPHROLOGIST:</label>
                            <div class="form-group px-3">
                                <div class="row ">
                                    <div class="col-12 bottom-line2 text-uppercase text-center text-primary">
                                        &nbsp;{{ $NEPRHO_NAME }}
                                    </div>
                                    <div class="col-12  text-center">
                                        <b> SPECIAL ENDORSEMENT</b>
                                    </div>
                                    @foreach ($SE_PARTS as $parts)
                                        @php
                                            $SE_COUNT++;
                                        @endphp
                                        <div class="col-12  text-center bottom-line2 ">
                                            &nbsp;{{ $parts }}
                                        </div>
                                    @endforeach
                                    @if ($SE_COUNT < 8)
                                        @for ($i = $SE_COUNT; $i < 8; $i++)
                                            <div class="col-12  text-center bottom-line2 ">
                                                &nbsp;
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                            </div>
                        </div>
                        <div class="col-4 left-line">
                            <label class="text-sm">DIAGNOSIS:</label>
                            <div class="form-group px-3">
                                <div class="row">
                                    <div class="col-12 bottom-line2 text-uppercase text-center text-primary">
                                        &nbsp; {{ $DIAGNOSIS }}
                                    </div>
                                    <div class="col-12  text-center">
                                        <b>STANDING ORDER</b>
                                    </div>
                                    @foreach ($SO_PARTS as $parts)
                                        @php
                                            $SO_COUNT++;
                                        @endphp
                                        <div class="col-12  text-center bottom-line2 ">
                                            &nbsp;{{ $parts }}
                                        </div>
                                    @endforeach
                                    @if ($SO_COUNT < 8)
                                        @for ($i = $SO_COUNT; $i < 8; $i++)
                                            <div class="col-12  text-center bottom-line2 ">
                                                &nbsp;
                                            </div>
                                        @endfor
                                    @endif

                                </div>

                            </div>
                        </div>
                        <div class="col-4 text-center left-line right-line">
                            <div class="row bottom-line">
                                <div class="col-12">
                                    <label>FISTULA / GRAFT ACCESS</label>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs">ACCESS TYPE</div>
                                    <div class="text-xs">BRUIT</div>
                                    <div class="text-xs">THRILL</div>
                                    <div class="text-xs">HEMATOMA</div>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs">
                                        @if ($AT_FISTULA)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif FISTULA
                                    </div>
                                    <div class="text-xs">
                                        @if ($B_STRONG)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif STRONG
                                    </div>
                                    <div class="text-xs">
                                        @if ($T_STRONG)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif STRONG
                                    </div>
                                    <div class="text-xs">
                                        @if ($H_PRESENT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif PRESENT
                                    </div>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs">
                                        @if ($AT_GRAFT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif GRAFT
                                    </div>
                                    <div class="text-xs">
                                        @if ($B_WEEK)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif WEAK
                                    </div>
                                    <div class="text-xs">
                                        @if ($T_WEAK)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif WEAK
                                    </div>
                                    <div class="text-xs">
                                        @if ($H_ABSENT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif ABSENT
                                    </div>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs">
                                        @if ($AT_RIGHT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                            @endif R @if ($AT_LEFT)
                                            [&#10003;]@else[&nbsp;&nbsp;]
                                            @endif L
                                    </div>
                                    <div class="text-xs">
                                        @if ($B_ABSENT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif ABSENT
                                    </div>
                                    <div class="text-xs">
                                        @if ($T_ABSENT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif ABSENT
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-xs mb-2">
                                        <b>
                                            <div class="row">
                                                <div class="col-4 text-right">
                                                    OTHERS:
                                                </div>
                                                <div class="col-7 bottom-line2 text-left">
                                                    &nbsp;{{ $H_OTHER_NOTES }}
                                                </div>
                                            </div>
                                        </b>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label>CVC ACCESS</label>
                                </div>
                                <div class="col-3">
                                    <div class="text-xs">
                                        @if ($CVC_SUBCATH)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif SUBCATH
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-xs">
                                        @if ($CVC_JUGCATH)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif JUGCATH
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-xs">
                                        @if ($CVC_FEMCATCH)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif FEMCATH
                                    </div>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs" style="width:100px;">
                                        @if ($CVC_PERMACATH)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                        @endif PERMCATH
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-xs"> LOCATION : </div>
                                </div>
                                <div class="col-3 text-left">
                                    <div class="text-xs">
                                        @if ($CVC_RIGHT)
                                        [&#10003;]@else[&nbsp;&nbsp;]
                                            @endif R @if ($CVC_LEFT)
                                            [&#10003;]@else[&nbsp;&nbsp;]
                                            @endif L
                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-xs"> CATHETER PORTS </div>
                                            <div class="text-xs text-right"> GOOD FLOW </div>
                                            <div class="text-xs text-right"> W/ RESISTANCE </div>
                                            <div class="text-xs text-right"> CLOTTED/NON PATENT </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="text-xs"> ARTERIAL </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_GOOD_FLOW_A)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_W_RESISTANCE_A)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_CLOTTED_A)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="text-xs"> VENOUS </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_GOOD_FLOW_V)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_W_RESISTANCE_V)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>
                                            <div class="text-xs text-center">
                                                @if ($CVC_CLOTTED_V)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 left-line right-line bottom-line">
                    <div class="row">
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 bottom-line text-center">
                                    <b>PRE-HEMODIALYSIS ASSESSMENT</b>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            MOBILIZATION
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($PRE_AMBULATORY)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif AMBULATORY
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_AMBULATORY_W_ASSIT)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif AMBULATORY W/ ASSIT
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_WHEEL_CHAIR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif WHEEL CHAIR
                                            </div>
                                            <div class="text-xs text-center">L.O.C</div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_CONSCIOUS)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CONSCIOUS
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_COHERENT)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif COHERENT
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_DISORIENTED)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DISORIENTED
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_DROWSY)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DROWSY
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            LUNGS
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($PRE_CLEAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CLEAR
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_CRACKLES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CRACKLES
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_RHONCHI)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif RHONCHI
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_WHEEZES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif WHEEZES
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_RALES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif RALES
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            FLUID STATUS
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($PRE_DISTENDED_JUGULAR_VIEW)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DISTENDED JUGULAR VIEW
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_ASCITES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif ASCITES
                                            </div>
                                            <div class="text-xs text-left"> </div>
                                            <div class="text-xs text-left">
                                                @if ($PRE_EDEMA)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif EDEMA
                                            </div>
                                            <div class="text-xs text-left row">
                                                <div class="col-6">
                                                    @if ($PRE_LOCATION)
                                                    [&#10003;]@else[&nbsp;&nbsp;]
                                                    @endif LOCATION:
                                                </div>
                                                <div class="col-6 bottom-line2 text-left">
                                                    &nbsp;{{ $PRE_LOCATION_NOTES }}
                                                </div>
                                            </div>
                                            <div class="text-xs text-left row">
                                                <div class="col-5">
                                                    @if ($PRE_DEPTH)
                                                    [&#10003;]@else[&nbsp;&nbsp;]
                                                    @endif DEPTH:
                                                </div>
                                                <div class="col-7 bottom-line2 text-left">
                                                    &nbsp;{{ $PRE_DEPTH_NOTES }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            HEART
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($PRE_REGULAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif REGULAR
                                            </div>
                                            <div class="text-xs text-left float-left" style="width:200px;">
                                                @if ($PRE_IRREGULAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif IRREGULAR
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-xs text-right"> SIGNATURE:
                                        _______________________________________________</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 left-line">
                            <div class="row">
                                <div class="col-12 bottom-line text-center">
                                    <b>POST-HEMODIALYSIS ASSESSMENT</b>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            MOBILIZATION
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($POST_AMBULATORY)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif AMBULATORY
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_AMBULATORY_W_ASSIT)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif AMBULATORY W/ ASSIT
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_WHEEL_CHAIR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif WHEEL CHAIR
                                            </div>
                                            <div class="text-xs text-center">L.O.C</div>
                                            <div class="text-xs text-left">
                                                @if ($POST_CONSCIOUS)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CONSCIOUS
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_COHERENT)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif COHERENT
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_DISORIENTED)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DISORIENTED
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_DROWSY)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DROWSY
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            LUNGS
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($POST_CLEAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CLEAR
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_CRACKLES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif CRACKLES
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_RHONCHI)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif RHONCHI
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_WHEEZES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif WHEEZES
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_RALES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif RALES
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            FLUID STATUS
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($POST_DISTENDED_JUGULAR_VIEW)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif DISTENDED JUGULAR VIEW
                                            </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_ASCITES)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif ASCITES
                                            </div>
                                            <div class="text-xs text-left"> </div>
                                            <div class="text-xs text-left">
                                                @if ($POST_EDEMA)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif EDEMA
                                            </div>
                                            <div class="text-xs text-left row">
                                                <div class="col-6">
                                                    @if ($POST_LOCATION)
                                                    [&#10003;]@else[&nbsp;&nbsp;]
                                                    @endif LOCATION:
                                                </div>
                                                <div class="col-6 bottom-line2 text-left">
                                                    &nbsp;{{ $POST_LOCATION_NOTES }}
                                                </div>
                                            </div>
                                            <div class="text-xs text-left row">
                                                <div class="col-5">
                                                    @if ($POST_DEPTH)
                                                    [&#10003;]@else[&nbsp;&nbsp;]
                                                    @endif DEPTH:
                                                </div>
                                                <div class="col-7 bottom-line2 text-left">
                                                    &nbsp;{{ $POST_DEPTH_NOTES }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="row">
                                        <div class="col-12 text-center font-weight-bold">
                                            HEART
                                        </div>
                                        <div class="col-12">
                                            <div class="text-xs text-left">
                                                @if ($POST_REGULAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif REGULAR
                                            </div>
                                            <div class="text-xs text-left float-left" style="width:200px;">
                                                @if ($POST_IRREGULAR)
                                                [&#10003;]@else[&nbsp;&nbsp;]
                                                @endif IRREGULAR
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-xs text-right"> SIGNATURE:
                                        _______________________________________________</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table width="100%" class="col-12 left-line right-line bottom-line">
                    <thead>
                        <tr class="text-center">
                            <th class="col-1">TIME</th>
                            <th class="col-1 left-line">BP</th>
                            <th class="col-1 left-line">HR</th>
                            <th class="col-1 left-line">BFR</th>
                            <th class="col-1 left-line">AP | VP</th>
                            <th class="col-1 left-line">TFR</th>
                            <th class="col-1 left-line">TMP</th>
                            <th class="col-1 left-line">HEPARIN | <br /> FLUSHING</th>
                            <th class="col-4 left-line">NURSES NOTES</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($noteList as $list)
                            <tr class="text-center top-line">
                                <td>{{ date('h:i:s A', strtotime($list->TIME)) }}</td>
                                <td class="left-line">{{ $list->BP_1 }}/{{ $list->BP_2 }}</td>
                                <td class="left-line">{{ $list->HR }}</td>
                                <td class="left-line">{{ $list->BFR }}</td>
                                <td class="left-line">{{ $list->AP }}|{{ $list->VP }}</td>
                                <td class="left-line">{{ $list->TFP }}</td>
                                <td class="left-line">{{ $list->TMP }}</td>
                                <td class="left-line">{{ $list->HEPARIN }}|{{ $list->FLUSHING }}</td>
                                <td class="left-line">{{ $list->NOTES }}</td>
                            </tr>
                            @php
                                $r++;
                            @endphp
                        @endforeach
                        @for ($i = $r; $i < $e; $i++)
                            <tr class="text-center top-line">
                                <td></td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line">|</td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line">|</td>
                                <td class="left-line"></td>
                            </tr>
                        @endfor

                        {{-- @foreach ($collection as $item)
                            <tr class="text-center top-line">
                                <td></td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line">|</td>
                                <td class="left-line"></td>
                                <td class="left-line"></td>
                                <td class="left-line">|</td>
                                <td class="left-line"></td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
                <div class="col-12 left-line right-line bottom-line">
                    <div class="row mt-2 px-2">
                        <div class="col-4">
                            <div class="row">
                                <div class="col-5 text-sm text-left"><b>CANNULATED&nbsp;BY&nbsp;:</b></div>
                                <div class="col-7 text-sm bottom-line2"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-sm text-left"> <b>NO&nbsp;OF&nbsp;ATTEMPT:</b> </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4  text-right text-sm">
                                            <b>ARTERIAL:</b>
                                        </div>
                                        <div class="col-3 text-sm bottom-line2">
                                        </div>
                                        <div class="col-2 text-right text-sm">
                                            <b>G:</b>
                                        </div>
                                        <div class="col-3 text-sm bottom-line2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-sm text-left"> </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-4  text-right text-sm">
                                            <b>VENOUS:</b>
                                        </div>
                                        <div class="col-3 text-sm bottom-line2">
                                        </div>
                                        <div class="col-2 text-right text-sm">
                                            <b>G:</b>
                                        </div>
                                        <div class="col-3 text-sm bottom-line2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-4 text-sm text-left"><b>PRIMED&nbsp;BY&nbsp;:</b></div>
                                <div class="col-8 text-sm bottom-line2"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-sm text-left"><b>INITIATED&nbsp;BY&nbsp;:</b></div>
                                <div class="col-8 text-sm bottom-line2"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-sm text-left"><b>TERMINATED&nbsp;BY&nbsp;:</b></div>
                                <div class="col-8 text-sm bottom-line2"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col-2 text-sm text-left"><b>RML&nbsp;:</b></div>
                                <div class="col-10 text-sm bottom-line2 px-1"></div>
                            </div>
                            <div class="row">
                                <div class="col-4 text-sm text-left"><b>HEPA&nbsp;PROFILE&nbsp;:</b></div>
                                <div class="col-8 text-sm bottom-line2"></div>
                            </div>

                        </div>
                        <div class="col-12 pb-1">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4 text-sm text-left">
                                            <b>EPO&nbsp;GIVEN:</b>&nbsp;[&nbsp;&nbsp;]<b>NO</b>&nbsp;&nbsp;[&nbsp;&nbsp;]<b>YES</b>
                                        </div>
                                        <div class="col-8 text-sm text-left bottom-line2"></div>
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4 text-sm text-right">
                                            <b>GIVEN&nbsp;BY: </b>
                                        </div>
                                        <div class="col-8 text-sm text-left bottom-line2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
