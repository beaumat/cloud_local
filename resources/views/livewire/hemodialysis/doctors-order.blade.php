<div class="row">

    <div class="col-md-10 col-10">
        <div class="input-group input-group-sm">
            <input type='text' class='form-control form-control-sm' wire:model='DOCTOR_ORDER'
                placeholder="Doctors Order Description" />
        </div>
    </div>
    <div class="col-md-2 col-2">
        <button class="btn btn-sm btn-success" wire:click='saveIt()'>
            <i class="fa fa-check" aria-hidden="true"></i> Update
        </button>
    </div>
    <div class="col-12 mt-4">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    </div>
</div>
