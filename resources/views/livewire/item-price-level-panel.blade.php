<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-secondary">
        <h3 class="card-title">Price Level</h3>
    </div>
    <div class="card-body">
        <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <form wire:submit.prevent='saveItem'>
                            <div class="mb-1 row">
                                <div class="col-md-8">
                                    <livewire:select-option name="PRICE_LEVEL_ID" titleName="Price Level"
                                        :options="$priceLevels" :zero="true" wire:model='PRICE_LEVEL_ID'
                                        :vertical="false">
                                </div>
                                <div class="col-md-4">
                                    <livewire:number-input name="CUSTOM_PRICE" titleName="Custom Price"
                                        wire:model='CUSTOM_PRICE' :vertical="false">
                                </div>
                                <div class="col-12 text-right">
                                    <button class="text-white btn bg-secondary btn-xs w-25 mt-2 mb-1s">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                     
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-secondary">
                                <tr>
                                    <th>Price Levels</th>
                                    <th class="col-2">Custom Price</th>
                                    <th class="col-2">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
