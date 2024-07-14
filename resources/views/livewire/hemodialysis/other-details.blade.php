<div>
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header bg-secondary text-xs">
    
            </div>
            <div class="card-body p-2">
             @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <form id="quickForm" wire:submit.prevent='save'>
                    <div class="form-group row">
                        <div class="col-md-6 m-1 p-1 border border-secondary">
                            <div class="row">
                                <div class=col-6>
                                    <label class='text-xs'>SPECIAL ORDER :</label>
                                    <textarea class="form-control form-control-sm" rows='6' wire:model='SE_DETAILS'></textarea>
                                </div>
                                <div class=col-6>
                                    <label class='text-xs'>STANDING ORDER :</label>
                                    <textarea class="form-control form-control-sm" rows='6' wire:model='SO_DETAILS'></textarea>
                                </div>
                                <div class='col-12 text-xs'>
                                    <input type="checkbox" class="mt-2" wire:model='DETAILS_USE_NEXT' /> Use for the
                                    next Treatment?
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 bg-light m-1 p-1 border border-secondary">
                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs '>BFR :</label></div>
                                <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='BFR' />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right "> <label class='text-xs'>DFR : </label></div>
                                <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DFR' />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs '>DURATION :</label></div>
                                <div class="col-6"> <input type='number' class='w-50 text-xs' wire:model='DURATION' /> <i class="text-xs">hrs</i> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs '>DIALYZER :</label></div>
                                <div class="col-6"> <input type='text' class='w-75 text-xs' wire:model='DIALYZER'
                                        maxlength='10' /> </div>
                            </div>
                        </div>
                        <div class="col-md-3 p-1">
                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs'>NA :</label></div>
                                <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_N'
                                        maxlength='7' /> </div>
                            </div>

                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs'>K+ :</label></div>
                                <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_K'
                                        maxlength='7' /> </div>
                            </div>

                            <div class="row">
                                <div class="col-6 text-right"> <label class='text-xs'>Ca+ :</label></div>
                                <div class="col-6"> <input type='text' class='w-50 text-xs' wire:model='DIALSATE_C'
                                        maxlength='7' /> </div>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <button type="submit" class="btn btn-sm btn-info">
                            Save change
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
