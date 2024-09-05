<div class="font-weight-light p-3">
    <div class="row">
        <div class="col-12 blackbox2">


        </div>
        <div class="col-12 ubox2 font-weight-light">
            <div class='row'>
                <div class="col-12">
                    <span style='font-size:17px' class="font-weight-bold"> 10. Accreditation Number/Name of Accredited
                        Health Care
                        Professional/Date Signed and Professional Fees/Charges </span>
                </div>
                <div class="col-12">
                    <span class="text-sm" style="margin-left:10px;">(Use additional CF2 if necessary):</span>
                </div>
                <div class="col-12 text-xs">
                    <div class="row">

                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <span> Accreditation number/Name of Accredited Health Care Professional/Date
                                        Signed</span>
                                </div>
                                <div class="col-6 text-center">
                                    <span> Details</span>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row mt-1">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2">
                                                &nbsp;
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-xs">Signature Over Printed Name</span>

                                        <div class="row " style="margin-left:20px; margin-bottom:-16px;">

                                            <div class="col-3 text-xs text-right">
                                                <span> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 5, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">

                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 6, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 8, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 9, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 0, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 1, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 2, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 3, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div> &nbsp;&nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;&nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;"> With co-pay on
                                                        top of
                                                        PhilHealth Benefit &nbsp;&nbsp;&nbsp;&nbsp;P</span>
                                                </div>
                                                <div class="col-6 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row mt-1">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2">
                                                &nbsp;
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-xs">Signature Over Printed Name</span>

                                        <div class="row " style="margin-left:20px; margin-bottom:-16px;">

                                            <div class="col-3 text-xs text-right">
                                                <span> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 5, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">

                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 6, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 8, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 9, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 0, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 1, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 2, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 3, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div> &nbsp;&nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;&nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;"> With co-pay
                                                        on
                                                        top of
                                                        PhilHealth Benefit &nbsp;&nbsp;&nbsp;&nbsp;P</span>
                                                </div>
                                                <div class="col-6 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="col-12 top-line2">
                            <div class="row">
                                <div class="col-6 text-center right-line2">
                                    <div class="form-group row">
                                        <div class="col-12 mb-1" style="height: 10px; ">
                                            <span> Accreditation No.: </span>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                            <label class="px-1">&nbsp;-</label>
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row mt-1">
                                            <div class="col-2"></div>
                                            <div class="col-8 bottom-line2">
                                                &nbsp;
                                            </div>
                                            <div class="col-2"></div>
                                        </div>
                                        <span class="text-xs">Signature Over Printed Name</span>

                                        <div class="row " style="margin-left:20px; margin-bottom:-16px;">

                                            <div class="col-3 text-xs text-right">
                                                <span> Date Signed:</span>
                                            </div>
                                            <div class="col-9 text-left">
                                                <div class="form-group text-md" style="width:300px;">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 5, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">

                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 6, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 8, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 9, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <label class="px-1">&nbsp;-</label>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 0, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 1, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 2, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        @if ($DATE_ADMITTED)
                                                            {{ substr($DATE_ADMITTED, 3, 1) }}
                                                        @else
                                                            &nbsp;
                                                        @endif
                                                    </div>
                                                    <br>
                                                    <p style="position: absolute;top:27px;" class="text-xs">
                                                        &nbsp; &nbsp;month
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;day
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp; &nbsp;&nbsp;
                                                        year</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 text-left">
                                    <div class="row" style="top:20px;position:relative;">
                                        <div class="col-12">
                                            <div class="box text-primary courier-new font-weight-bold">
                                                &nbsp;
                                            </div> &nbsp;&nbsp;
                                            No co-pay on top of PhilHealth Benefit
                                        </div>
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="box text-primary courier-new font-weight-bold">
                                                        &nbsp;
                                                    </div> &nbsp;&nbsp;
                                                    <span style="position:absolute;top:5px;width:300px;"> With co-pay
                                                        on
                                                        top of
                                                        PhilHealth Benefit &nbsp;&nbsp;&nbsp;&nbsp;P</span>
                                                </div>
                                                <div class="col-6 ">
                                                    <div class='form-group bottom-line2'>
                                                        <span>
                                                            &nbsp;
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 text-center font-weight-light bgBlack pb-2">
            <b class="text-white arial font-weight-bold" style="font-size: 18px">
             PART III - CERTIFICATION OF CONSUMPTION OF BENEFITS AND CONSENT TO ACCESS PATIENT RECORD/S
            </b><br/>
            <span class="text-white text-sm">NOTE: Member/Patient should sign only after the applicable charges have been filled-out</span>
        </div>
       <div class="col-12 ubox2 font-weight-light">
                 <label style='font-size:17px;height:20px;'> A. CERTIFICATION OF CONSUMPTION OF BENEFITS:</label>
       </div>



    </div>
</div>
