<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-7 mt-2">

        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right text-danger" for="company_nane">
                <i>Warning if Transaction are :</i>
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='DateWarningDaysPast' type="number" name="before_past_day"
                        id="before_past_day" class="form-control form-control-sm">
                    <span class="input-group-append">
                        <button wire:click="saveOn('DateWarningDaysPast','{{ $DateWarningDaysPast }}')" type="button"
                            class="btn btn-sm btn-success btn-flat">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>
            <label class=" col-md-4 col-label-form text-info" for="company_nane">Days in Past</label>
        </div>


    </div>
    <div class="col-md-7 mt-2">

        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right text-danger" for="company_nane">
                <i>Warning if Transaction are :</i>
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='DateWarningDaysFuture' type="number" name="DateWarningDaysFuture"
                        id="DateWarningDaysFuture" class="form-control form-control-sm">
                    <span class="input-group-append">
                        <button wire:click="saveOn('DateWarningDaysFuture','{{ $DateWarningDaysFuture }}')"
                            type="button" class="btn btn-sm btn-success btn-flat">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>
            <label class=" col-md-4 col-label-form text-info" for="company_nane">Days in future</label>
        </div>
    </div>
    <div class="col-md-7 mt-2">
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-right" for="company_nane">Closing Date :</label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='ClosingDate' type="date" name="ClosingDate" id="ClosingDate"
                        class="form-control form-control-sm">
                    <span class="input-group-append">
                        <button wire:click="saveOn('ClosingDate','{{ $ClosingDate }}')" type="button"
                            class="btn btn-sm btn-success btn-flat">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7 mt-2">
        <div class="form-group row">
            <label class=" col-md-4 col-label-form text-right " for="company_nane">
                Round amount to nearest :
            </label>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input wire:model.live.debounce='SmallestCurrencyValue' type="number" name="SmallestCurrencyValue" id="SmallestCurrencyValue"
                        class="form-control form-control-sm">
                    <span class="input-group-append">
                        <button wire:click="saveOn('SmallestCurrencyValue','{{ $SmallestCurrencyValue }}')" type="button" class="btn btn-sm btn-success btn-flat">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <!-- checkbox -->
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <button wire:click="saveOn('SkipJournalEntry','{{ $SkipJournalEntry }}')"
                    class="btn btn-sm btn-success mr-4">
                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                </button>
                <input type="checkbox" wire:model.live='SkipJournalEntry'>
                <label>Skip Journal Entry</label>
               

            </div>
        </div>
    </div>

</div>
