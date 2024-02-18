<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-6">
        <label for="company_nane">Taxplayer ID No.</label>
        <div class="input-group input-group-sm">
            <input wire:model.live.debounce='CompanyTin' type="text" name="CompanyTin" id="CompanyTin"
                class="form-control form-control-sm">
            <span class="input-group-append">
                <button wire:click="saveOn('CompanyTin','{{ $CompanyTin }}')" type="button"
                    class="btn btn-sm btn-success btn-flat">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </span>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-md-4  col-form-label-sm text-right">
                Output Tax :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='OutputTaxId' name="OutputTaxId" id="OutputTaxId"
                    class="form-control form-control-sm">
                    @foreach ($taxList as $list)
                        <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                    @endforeach
                </select>

                <span class="input-group-append">
                    <button wire:click="saveOn('OutputTaxId','{{ $OutputTaxId }}')" type="button"
                        class="btn btn-sm btn-success">
                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                    </button>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-4  col-form-label-sm text-right">
                Input Tax :
            </label>
            <div class="col-md-8 input-group input-group-sm">
                <select wire:model.live.debounce='InputTaxId' name="InputTaxId" id="InputTaxId"
                    class="form-control form-control-sm">
                    @foreach ($taxList as $list)
                        <option value="{{ $list->ID }}">{{ $list->NAME }}</option>
                    @endforeach
                </select>

                <span class="input-group-append">
                    <button wire:click="saveOn('InputTaxId','{{ $InputTaxId }}')" type="button"
                        class="btn btn-sm btn-success">
                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>
  
</div>
