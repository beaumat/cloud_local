<div class="card bg-light">
    <h5 class="px-2 card-title">Related Units </h5>
    <div class="card-body ">
        <form wire:submit.prevent='saveItem'>
            <div class="mb-1 row">
                <div class="col-md-6">
                    <livewire:select-option name="UNIT_ID" titleName="Units" :options="$units"
                        :zero="true" wire:model='UNIT_ID' :vertical="true">
                </div>
                <div class="col-md-6">
                    <livewire:text-input name="BARCODE" titleName="Barcode" wire:model='BARCODE'
                        :vertical="true">
                </div>
                <div class="col-md-6">
                    <livewire:number-input name="QUANTITY" titleName="Quantity"
                        wire:model='QUANTITY' :vertical="true">
                </div>
                <div class="col-md-6">
                    <livewire:number-input name="RATE" titleName="Rate" wire:model='RATE'
                        :vertical="true">
                </div>

                <div class="text-right col-md-12">
                    <button class="text-white btn bg-info btn-xs w-25">
                        <i class="fas fa-plus"></i> Add
                    </button>

                </div>
            </div>
        </form>
     
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-info">
                <tr>
                    <th>Units</th>
                    <th class="text-right col-2">Symbol</th>
                    <th class="text-right col-1">Qty</th>
                    <th class="text-right col-2">Rate</th>

                    <th>Barcode</th>
                    <th class="text-center col-2">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm">

            </tbody>
        </table>
    </div>
</div>