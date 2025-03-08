    <div class="card card-primary @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-wheelchair" aria-hidden="true"></i> Patient Summary</h3>
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
                        <div class="text-xs">Month</div>
                        <select class="text-xs w-100" wire:model.live='month'>
                            @foreach ($monthlyList as $list)
                                <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-4'>
                        <div class="text-xs">Year</div>
                        <select class="text-xs w-100" wire:model.live='year'>
                            @foreach ($yearList as $list)
                                <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th class="text-center">New</th>
                                    <th class="text-center">Confinement</th>
                                    <th class="text-center">Transfer</th>
                                    <th class="text-center">Expired</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locationList as $list)
                                    <tr>
                                        <td>{{ $list->NAME }}</td>
                                        <td class="text-center">{{ $list->NEW }}</td>
                                        <td class="text-center">{{ $list->CONFINEMENT }}</td>
                                        <td class="text-center">{{ $list->TRANSFER }}</td>
                                        <td class="text-center">{{ $list->EXPIRED }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
