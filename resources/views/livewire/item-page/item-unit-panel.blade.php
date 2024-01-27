<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-sky">
        <h3 class="card-title">Units</h3>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        @livewire('ItemPage.ItemUnitRelatedUnit', ['itemId' => $itemId])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">   
                        @livewire('ItemPage.ItemUnitPriceLevelUnit', ['itemId' => $itemId])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class=" card-body">
                <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        @livewire('ItemPage.ItemUnitLocationDefault', ['itemId' => $itemId])
                    </div>
                </div>
            </div>
        </div>
    </div>







</div>
