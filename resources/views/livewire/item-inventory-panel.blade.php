<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-dark">
        <h3 class="card-title">Inventory</h3>
    </div>

    <div class="card-body">
        <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <form wire:submit.prevent='saveItem'>
                            <div class="mb-1 row">
                                <div class="col-md-6">
                                    <livewire:select-option name="LOCATION_ID" titleName="Location" :options="$locations"
                                        :zero="true" wire:model='LOCATION_ID' :vertical="true">
                                </div>
                                <div class="col-md-6">
                                    <livewire:number-input name="ORDER_POINT" titleName="Order Point"
                                        wire:model='ORDER_POINT' :vertical="true">
                                </div>
                                <div class="col-md-6">
                                    <livewire:number-input name="ORDER_QTY" titleName="Order Qty" wire:model='ORDER_QTY'
                                        :vertical="true">
                                </div>

                                <div class="col-md-6">
                                    <livewire:number-input name="ORDER_LEADTIME" titleName="Leadtime"
                                        wire:model='ORDER_LEADTIME' :vertical="true">
                                </div>

                                <div class="col-md-6">
                                    <livewire:number-input name="ONHAND_MAX_LIMIT" titleName="Max Limit"
                                        wire:model='ONHAND_MAX_LIMIT' :vertical="true">
                                </div>
                                <div class="col-md-6">
                                    <livewire:select-option name="STOCK_BIN_ID" titleName="Stock Bin" :options="[]"
                                        :zero="true" wire:model='STOCK_BIN_ID' :vertical="true">
                                </div>
                                <div class="col-md-12 text-right">
                                    <button class="text-white btn bg-dark btn-xs w-25 mt-2 mb-1">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                        {{-- <div class="row">
                            <div class="mb-1 col-md-12">
                                <input type="text" wire:model.live.debounce.150ms='search'
                                    class="w-100 form-control form-control-sm bg-light" placeholder="Search" />
                            </div>
                        </div> --}}
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-dark">
                                <tr>
                                    <th>Location</th>
                                    <th class="text-right col-1">Point</th>
                                    <th class="text-right col-1">Quantity</th>
                                    <th class="text-right col-2">Lead-time</th>
                                    <th class="text-right col-3">Onhand Max-Limit</th>
                                    <th>Stock Bin</th>
                                    <th class="text-center col-2">Action </th>
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
