<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-light-blue">
        <h3 class="card-title">Units</h3>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-related-unit :itemId="$itemId" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-price-level-unit :itemId="$itemId" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class=" card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <livewire:item-unit-location-default :itemId="$itemId" />
                    </div>
                </div>
            </div>
        </div>
    </div>







</div>
