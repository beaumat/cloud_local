<div class="mt-2">
    @if ($vertical)
        <div class="row">

            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-sm">{{ $titleName }}</label>
                @endif
            </div>

            <div class="col-9">
                <input type="text" autocomplete="off" wire:model='value' class="text-sm form-control form-control-sm"
                    id="{{ $name }}"
                    @if ($withLabel) placeholder="Enter {{ Str::lower($titleName) }}" @endif
                    @if ($isDisabled) disabled @endif />
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-sm">{{ $titleName }}</label>
        @endif
        <input type="text" autocomplete="off" wire:model='value' class="text-sm form-control form-control-sm"
            id="{{ $name }}"
            @if ($withLabel) placeholder="Enter {{ Str::lower($titleName) }}" @endif
            @if ($isDisabled) disabled @endif />
    @endif
</div>
