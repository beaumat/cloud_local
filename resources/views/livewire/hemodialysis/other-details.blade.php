<div>
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-secondary text-xs">
            </div>
            <div class="card-body p-2">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="form-group row">
                    <div class="col-md-12  border-bottom border-secondary">
                        <div class="row">
                            <div class=col-8>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class='text-sm'>SPECIAL ORDER : <i
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
                                <label class='text-sm'>STANDING ORDER :</label>
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
                    <div class="col-md-5 bg-light m-1 p-1 border border-secondary">
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs '>BFR :</label></div>
                            <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='BFR'
                                    @if ($Modify == false) disabled @endif />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-right "> <label class='text-xs'>DFR : </label></div>
                            <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DFR'
                                    @if ($Modify == false) disabled @endif />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs '>DURATION :</label></div>
                            <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DURATION'
                                    @if ($Modify == false) disabled @endif />
                                <i class="text-xs">hrs</i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs '>DIALYZER :</label></div>
                            <div class="col-6"> <input type='text' class='w-75 text-xs' wire:model='DIALYZER'
                                    maxlength='10' @if ($Modify == false) disabled @endif /> </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs '>HEPARIN :</label></div>
                            <div class="col-6"> <input type='text' class='w-75 text-xs' wire:model='HEPARIN'
                                    maxlength='10' @if ($Modify == false) disabled @endif /> </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs '>REUSE NO :</label></div>
                            <div class="col-6"> <input type='text' class='w-75 text-xs' wire:model='REUSE_NO'
                                    maxlength='10' @if ($Modify == false) disabled @endif /> </div>
                        </div>
                    </div>
                    <div class="col-md-5 p-1">
                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs'>NA :</label></div>
                            <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_N'
                                    maxlength='7' @if ($Modify == false) disabled @endif /> </div>
                        </div>

                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs'>K+ :</label></div>
                            <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_K'
                                    maxlength='7' @if ($Modify == false) disabled @endif /> </div>
                        </div>

                        <div class="row">
                            <div class="col-6 text-right"> <label class='text-xs'>Ca+ :</label></div>
                            <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_C'
                                    maxlength='7' @if ($Modify == false) disabled @endif /> </div>
                        </div>
                    </div>
                </div>
                <div class='form-group'>
                    {{-- <button type="submit" class="btn btn-sm btn-info">
                            Save change
                        </button> --}}
                </div>

            </div>
        </div>
    </section>
</div>
