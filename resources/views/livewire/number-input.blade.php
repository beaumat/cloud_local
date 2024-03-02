<div class="mt-2">

    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
                @endif
            </div>
            <div class="col-9">
                <input type="number" step="any" type="text" id="decimalInput" wire:model='value'
                    class="text-xs text-right form-control form-control-sm2" id="{{ $name }}"                
                    @if ($isDisabled) disabled @endif />
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
        @endif
        <input type="number" step="any" type="text" id="decimalInput" wire:model='value'
            class="text-xs text-right form-control form-control-sm2" id="{{ $name }}"
      
            @if ($isDisabled) disabled @endif />
    @endif
</div>
