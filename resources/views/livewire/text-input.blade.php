<div class="mt-2">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                <label for="{{ $name }}" class="text-sm">{{ $titleName }}</label>
            </div>
            <div class="col-9">
                <input type="text" autocomplete="off" wire:model='value' class="text-sm form-control form-control-sm"
                    id="{{ $name }}" placeholder="Enter {{ Str::lower($titleName) }}">
            </div>
        </div>
    @else
        <label for="{{ $name }}" class="text-sm">{{ $titleName }}</label>
        <input type="text" autocomplete="off" wire:model='value' class="text-sm form-control form-control-sm"
            id="{{ $name }}" placeholder="Enter {{ Str::lower($titleName) }}">
    @endif
</div>
