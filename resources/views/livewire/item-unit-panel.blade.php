<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-info">
        <h3 class="card-title">Units</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row"
                    @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <h5 class="px-2 card-title">Related Units </h5>
                            <div class="card-body ">
                                <form wire:submit.prevent='saveItem'>
                                    <div class="mb-1 row">
                                        <div class="col-md-10">

                                        </div>
                                        <div class="text-right col-2">
                                            <button
                                                class="text-white btn bg-info btn-xs w-100">
                                                <i class="fas fa-plus"></i> Add
                                            </button>

                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="mb-1 col-md-12">
                                        <input type="text"
                                            wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm bg-light"
                                            placeholder="Search" />
                                    </div>
                                </div>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row"
                    @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <h5 class="px-2 card-title">Unit Price Level </h5>
                            <div class="card-body">
                                <form wire:submit.prevent='saveItem'>
                                    <div class="mb-1 row">
                                        <div class="col-md-10">
                                        </div>
                                        <div class="text-right col-2">
                                            <button
                                                class="text-white btn bg-info btn-xs w-100">
                                                <i class="fas fa-plus"></i> Add
                                            </button>

                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="mb-1 col-md-12">
                                        <input type="text"
                                            wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm bg-light"
                                            placeholder="Search" />
                                    </div>
                                </div>
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-info">
                                        <tr>

                                            <th>Price Levels
                                            <th class="text-right col-2">Custom Price</th>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2 card-body">
                <div class="row"
                    @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <h5 class="px-2 card-title">Location Default </h5>
                            <div class="card-body">
                                <form wire:submit.prevent='saveItem'>
                                    <div class="mb-1 row">
                                        <div class="col-md-10">
                                        </div>
                                        <div class="text-right col-2">
                                            <button
                                                class="text-white btn bg-info btn-xs w-100">
                                                <i class="fas fa-plus"></i> Add
                                            </button>

                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="mb-1 col-md-12">
                                        <input type="text"
                                            wire:model.live.debounce.150ms='search'
                                            class="w-100 form-control form-control-sm bg-light"
                                            placeholder="Search" />
                                    </div>
                                </div>
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-info">
                                        <tr>
                                            <th>Location</th>
                                            <th class="text-right col-2">Purchases Unit
                                            </th>
                                            <th class="text-right col-2">Sales Unit</th>
                                            <th class="text-right col-2">Shipping Unit</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>







</div>
