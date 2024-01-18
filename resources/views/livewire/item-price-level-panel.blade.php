<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-secondary">
        <h3 class="card-title">Price Level</h3>
    </div>
    <div class="card-body">
        <div class="row"
            @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent='saveItem'>
                            <div class="mb-1 row">
                                <div class="col-md-10">
                                    {{-- object --}}
                                </div>
                                <div class="text-right col-2">
                                    <button class="text-white btn bg-secondary btn-xs w-100">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="mb-1 col-md-12">
                                <input type="text" wire:model.live.debounce.150ms='search'
                                    class="w-100 form-control form-control-sm bg-light"
                                    placeholder="Search" />
                            </div>
                        </div>
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