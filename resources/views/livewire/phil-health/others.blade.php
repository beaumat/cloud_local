<div>

</div>
<form wire:submit.prevent='saveData'>
    <div class="modal-body">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' =>
        session('message'), 'error' => session('error')])
        <label>RR No.</label>
        <input type="text" class="form-control form-control-sm" wire:model='RR_NO' title="RR No."
            placeholder="RR NO." />
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-warning btn-md"> <i class="fa fa-lock" aria-hidden="true"></i> Update
        </button>

    </div>
</form>


</div>