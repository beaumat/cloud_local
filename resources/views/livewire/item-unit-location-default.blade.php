<div class="card bg-light">
    <h5 class="px-2 card-title">Location Default </h5>
    <div class="card-body">
        <form wire:submit.prevent='saveItem'>
            <div class="mb-1 row">
                <div class="col-md-3">
                    <livewire:select-option name="UNIT_LOCATION_ID" titleName="Location" :options="$locationList"
                    :zero="true" wire:model='UNIT_LOCATION_ID' :vertical="false">
                </div>
                <div class="col-md-3">
                    <livewire:select-option name="UNIT_PURCHASES_UNIT_ID" titleName="Purchases Unit" :options="$unitList"
                    :zero="true" wire:model='UNIT_PURCHASES_UNIT_ID' :vertical="false">
                </div>
                <div class="col-md-3">
                    <livewire:select-option name="UNIT_SALES_UNIT_ID" titleName="Sales Unit" :options="$unitList"
                    :zero="true" wire:model='UNIT_SALES_UNIT_ID' :vertical="false">
                </div>
                <div class="col-md-3">
                    <livewire:select-option name="UNIT_SHIPPING_UNIT_ID" titleName="Shipping Unit" :options="$unitList"
                    :zero="true" wire:model='UNIT_SHIPPING_UNIT_ID' :vertical="false">
                </div>
                <div class="text-right col-md-12">
                    <button class="text-white btn bg-light-blue btn-xs w-25 mt-2 mb-1">
                        <i class="fas fa-plus"></i> Add
                    </button>

                </div>
            </div>
        </form>
      
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-light-blue">
                <tr>
                    <th>Location</th>
                    <th class="text-right col-2">Purchases Unit</th>
                    <th class="text-right col-2">Sales Unit</th>
                    <th class="text-right col-2">Shipping Unit</th>
                    <th class="text-center col-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">

            </tbody>
        </table>
    </div>
</div>
