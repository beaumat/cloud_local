<div class='row'>
    <div class="col-md-6">
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-header bg-secondary text-xs">
                </div>
                <div class="card-body p-2">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class=col-8>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class='text-sm'>SPECIAL ORDER <i
                                                    class="text-primary text-xs">Current</i></label>
                                            <textarea class="form-control form-control-sm" rows='6' wire:model='SE_DETAILS'
                                                @if ($Modify == false) disabled @endif></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class='text-sm'>SPECIAL ORDER : <i
                                                    class="text-info text-xs">Next</i></label>
                                            <textarea class="form-control form-control-sm" rows='6' wire:model='SE_DETAILS_NEXT'
                                                @if ($Modify == false) disabled @endif></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group text-sm">
                                    <label  wire:click.live='detailsUseNext()'>Use for next treatment :</label>
                                    <input type="radio" name="DETAILS_USE_NEXT" class="mt-2"
                                        wire:model="DETAILS_USE_NEXT" value="true"
                                        @if ($Modify == false) disabled @endif />
                                    Yes
                                    <input type="radio" name="DETAILS_USE_NEXT" class="mt-2"
                                        wire:model="DETAILS_USE_NEXT" value="false"
                                        @if ($Modify == false) disabled @endif />
                                    No
                                </div> --}}
                                </div>
                                <div class=col-4>
                                    <label class='text-sm'>STANDING ORDER </label>
                                    <textarea class="form-control form-control-sm" rows='6' wire:model='SO_DETAILS'
                                        @if ($Modify == false) disabled @endif></textarea>
                                    <div class="form-group text-sm">
                                        <label wire:click.live='orderUseNext()'>Use for next treatment :</label>
                                        <input type="radio" name="ORDER_USE_NEXT" class="mt-2"
                                            wire:model="ORDER_USE_NEXT" value="true"
                                            @if ($Modify == false) disabled @endif />
                                        Yes
                                        <input type="radio" name="ORDER_USE_NEXT" class="mt-2"
                                            wire:model="ORDER_USE_NEXT" value="false"
                                            @if ($Modify == false) disabled @endif />
                                        No
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header text-xs">
                                    <div class="row ">
                                        <div class="col-12 text-left"> <label class='text-xs '>UF GOAL </label></div>
                                        <div class="col-12"> <input type='text' class='w-100 text-xs'
                                                wire:model='UF_GOAL' maxlength='80'
                                                @if ($Modify == false) disabled @endif />
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs '>BFR </label></div>
                                        <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='BFR'
                                                @if ($Modify == false) disabled @endif />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right "> <label class='text-xs'>DFR </label></div>
                                        <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DFR'
                                                @if ($Modify == false) disabled @endif />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs '>DURATION </label></div>
                                        <div class="col-6"> <input type='number' class='w-50 text-xs'
                                                wire:model='DURATION'
                                                @if ($Modify == false) disabled @endif />
                                            <i class="text-xs">hrs</i>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs '>DIALYZER </label></div>
                                        <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                wire:model='DIALYZER' maxlength='10'
                                                @if ($Modify == false) disabled @endif /> </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6 text-right"> <label class='text-xs '>REUSED NO. </label></div>
                                        <div class="col-6"> <input type='text' class='w-50 text-xs'
                                                wire:model='REUSE_NO' maxlength='10'
                                                @if ($Modify == false) disabled @endif /> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs text-info'
                                                style="width:100px;">NEXT REUSED NO.
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type='text' class='w-50 text-xs' wire:model='REUSE_NEXT'
                                                maxlength='10' @if ($Modify == false) disabled @endif />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs '>HEPARIN </label></div>
                                        <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                wire:model='HEPARIN' maxlength='10'
                                                @if ($Modify == false) disabled @endif /> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 text-right"> <label class='text-xs '>FLUSHING </label></div>
                                        <div class="col-6"> <input type='text' class='w-75 text-xs'
                                                wire:model='FLUSHING' maxlength='10'
                                                @if ($Modify == false) disabled @endif /> </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-8 ">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header text-xs">
                                            <strong> SAFETY CHECK</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 text-left">
                                                    <label class='text-xs'>
                                                        <input type='checkbox' wire:model='SC_MACHINE_TEST'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;MACHINE TEST
                                                    </label> <br />

                                                    <label class='text-xs'>
                                                        <input type='checkbox' wire:model='SC_SECURED_CONNECTIONS'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;SECURED CONNECTIONS
                                                    </label> <br />

                                                    <label class='text-xs'>
                                                        <input type='checkbox' wire:model='SC_SALINE_LINE_DOUBLE_CLAMP'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;SALINE LINE DOUBLE CLAMP
                                                    </label>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-4 text-right">
                                                            <label class='text-xs'>CONDUCTIVITY </label>
                                                        </div>
                                                        <div class="col-8">
                                                            <input type='text' class='w-12 text-xs'
                                                                wire:model='SC_CONDUCTIVITY' maxlength='7'
                                                                @if ($Modify == false) disabled @endif />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-5 text-right">
                                                            <label class='text-xs' style="width:100px;">DIALYSATE TEMP
                                                            </label>
                                                        </div>
                                                        <div class="col-7">
                                                            <input type='text' class='w-12 text-xs'
                                                                wire:model='SC_DIALYSATE_TEMP'
                                                                maxlength='7'
                                                                @if ($Modify == false) disabled @endif />
                                                        </div>
                                                    </div>

                                                    <label class='text-xs'>
                                                        <b>RESIDUAL TEST</b>
                                                        <input type='checkbox' wire:model='SC_RESIDUAL_TEST_NEGATIVE'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;:<b>NEGATIVE</b>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header text-xs">
                                            <strong> DIALYSATE BATH </strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">

                                                <div class="col-12 text-left">
                                                    <label class='text-xs'>
                                                        <input type='checkbox' wire:model='DB_STANDARD_HCOA'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;STANDARD HCOA
                                                    </label>
                                                </div>

                                                <div class="col-12 text-left">
                                                    <label class='text-xs'>
                                                        <input type='checkbox' wire:model='DB_ACID'
                                                            @if ($Modify == false) disabled @endif />
                                                        &nbsp;ACID
                                                    </label>
                                                </div>
                                                <div class="col-4 text-right"> <label class='text-xs'>NA </label>
                                                </div>
                                                <div class="col-8"> <input type='text' class='w-50 text-xs'
                                                        wire:model='DIALSATE_N' maxlength='7'
                                                        @if ($Modify == false) disabled @endif /> </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4 text-right"> <label class='text-xs'>K+ </label>
                                                </div>
                                                <div class="col-8"> <input type='text' class='w-50 text-xs'
                                                        wire:model='DIALSATE_K' maxlength='7'
                                                        @if ($Modify == false) disabled @endif /> </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4 text-right"> <label class='text-xs'>Ca+ </label>
                                                </div>
                                                <div class="col-8"> <input type='text' class='w-50 text-xs'
                                                        wire:model='DIALSATE_C' maxlength='7'
                                                        @if ($Modify == false) disabled @endif /> </div>
                                            </div>
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
    <div class="col-md-6">
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-header bg-dark active text-xs">
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class='col-md-6'>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
