    <div class="card card-yellow @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title">Sales Collection</h3>
            <div class="card-tools">
                <button type="button" wire:loading.attr='disabled' wire:click="onClickWid" class="btn btn-tool">
                    @if (!$isShow)
                        <i class="fas fa-plus"></i>
                    @else
                        <i class="fas fa-minus"></i>
                    @endif
                </button>
                <div wire:loading.delay>
                    <span class="spinner"></span>
                </div>
            </div>
        </div>
        <div class="card-body @if (!$isShow) d-none @endif">
            <div class="inner" style="height:300px;">
                <div class="row">
                    <div class="col-8">
                        &nbsp;
                    </div>
                    <div class='col-4'>
                        &nbsp;
                    </div>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>

                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
