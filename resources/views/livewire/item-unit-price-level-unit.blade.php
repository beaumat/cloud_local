<div class="card bg-light">
    <h5 class="px-2 card-title">Unit Price Level </h5>
    <div class="card-body">
        <form wire:submit.prevent='saveItem'>
            <div class="mb-1 row">
                <div class="col-md-4">
                    <livewire:select-option name="UNIT_RELATED_ID" titleName="Related Unit" :options="$unitRelated"
                    :zero="true" wire:model='UNIT_RELATED_ID' :vertical="false">
                </div>
       
                <div class="col-md-4">
                    <livewire:select-option name="UNIT_PRICE_LEVEL_ID" titleName="Price Level" :options="$priceLevels"
                    :zero="true" wire:model='UNIT_PRICE_LEVEL_ID' :vertical="false">
                </div>
                <div class="col-md-4">
                    <livewire:number-input name="CUSTOM_PRICE" titleName="Custom Price"
                    wire:model='CUSTOM_PRICE' :vertical="false">
                </div>
                <div class="text-right col-md-12">
                    <button class="text-white btn bg-info btn-xs w-25 mt-2 mb-1">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>
        </form>
    
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-info">
                <tr>
                    <th>Price Levels</th>
                    <th class="text-right col-2">Custom Price</th>
                    <th class="text-center col-2">Action </th>
                        
                   
                </tr>
            </thead>
            <tbody class="text-sm">

            </tbody>
        </table>
    </div>
</div>