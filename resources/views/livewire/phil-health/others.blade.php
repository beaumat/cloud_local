<div>


    <form wire:submit.prevent='saveData'>
        <div class="form-group">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' =>
            session('message'), 'error' => session('error')])
            <label class="text-xs">RR No.</label>
            <input type="text" class="form-control text-xs w-25"  wire:model='RR_NO' title="RR No."
                placeholder="RR NO." maxlength="3" />
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-xs"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Update
            </button>

        </div>
    </form>


</div>