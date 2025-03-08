    <div class="card card-orange @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-book" aria-hidden="true"></i> Receivables Aging Status</h3>
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
            <div class="inner">
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
                        <tr>
                            <th>Branch</th>
                            <th>Current</th>
                            <th>1-30</th>
                            <th>31-60</th>
                            <th>61-90</th>
                            <th>Over 90</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locationList as $list)
                            <tr>
                                <td>{{ $list->NAME }}</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                                <td class="text-right">0</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
