<section class="content">
    <div class="container-fluid">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title">
                    @if ($itemType === 1)
                        Component List
                    @else
                        Group List
                    @endif
                </h3>
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
                                        </div>
                                        <div class="text-right col-2">
                                            <button class="text-white btn bg-primary btn-xs w-100">
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
                                    <thead class="text-xs bg-primary">
                                        <tr>
                                            <th>Code</th>
                                            <th> Description</th>
                                            <th class="text-right col-1">Qty</th>
                                            <th class="text-right col-2">Rate</th>
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
    </div>
</section>
