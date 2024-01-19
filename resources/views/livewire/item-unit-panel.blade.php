<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-info">
        <h3 class="card-title">Units</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-related-unit :ITEM_ID="$itemId" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-price-level-unit :ITEM_ID="$itemId" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-location-default :ITEM_ID="$itemId" />
                    </div>
                </div>
            </div>
        </div>
    </div>







</div>
