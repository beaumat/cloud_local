<div wire:ignore class="mt-2">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                <label for="{{ $name }}" class="text-sm"> {{ $titleName }}</label>
            </div>
            <div class="col-9">
                <select wire:model='value' id="{{ $name }}" class="text-sm form-control form-control-sm">
                    @if ($zero)
                        <option value="0"> Choose {{ Str::lower($titleName) }}</option>
                    @endif
                    @foreach ($options as $option)
                        <option value="{{ $option->ID }}">
                            @if ($option->DESCRIPTION)
                                {{ $option->DESCRIPTION }}
                            @else
                                @if ($option->NAME)
                                    {{ $option->NAME }}
                                @else
                                    {{ $option->CODE }}
                                @endif
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    @else
        <label for="{{ $name }}" class="text-sm"> {{ $titleName }}</label>

        <select wire:model='value' id="{{ $name }}" class="text-sm form-control form-control-sm">
            @if ($zero)
                <option value="0"> Choose {{ Str::lower($titleName) }}</option>
            @endif
            @foreach ($options as $option)
                <option value="{{ $option->ID }}">
                    @if ($option->DESCRIPTION)
                        {{ $option->DESCRIPTION }}
                    @else
                        @if ($option->NAME)
                            {{ $option->NAME }}
                        @else
                            {{ $option->CODE }}
                        @endif
                    @endif
                </option>
            @endforeach
        </select>
    @endif

</div>

@script()
    <script>
        $(document).ready(function() {
            $('#{{ $name }}').select2();
            $('#{{ $name }}').on('change', function(event) {
                $wire.$set('value', event.target.value);
            });

        });
    </script>
@endscript
