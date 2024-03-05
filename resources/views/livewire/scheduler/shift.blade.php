<div id='{{ $ID }}' class=""
    @if ($CONTACT_ID === 0 || $STATUS_ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
    <div class="form-group">
        <div class="form-check">
            <label class="form-check-label">
                <input class="form-checkbox" wire:click="save({{ 0 }},'{{ $ID }}')" type="radio"
                    wire:model="SHIFT_ID" value="0" />
                No Shift
            </label>
        </div>
        @foreach ($shiftList as $list)
            <div class="form-check">
                <label @if ($SHIFT_ID == $list->ID) style="font-weight:bolder;color:blue" @endif class="form-check-label">
                    <input class="form-checkbox" wire:click="save({{ $list->ID }},'{{ $ID }}')"
                        type="radio" wire:model="SHIFT_ID" value="{{ $list->ID }}" />
                    {{ $list->NAME }}
                </label>
            </div>
        @endforeach

        
    </div>

</div>
