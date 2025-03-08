    <div class="card card-success @if (!$isShow) collapsed-card @endif">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-medkit" aria-hidden="true"></i> Philhealth Monitoring Summary</h3>
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
                        <tr>
                            <th class="col-3">Branch</th>
                            <th class="text-center col-2">Latest SOA Created</th>
                            <th class="text-center col-2"># of w/o Transmittal</th>
                            <th class="text-center col-2">Latest Due w/o Trans.</th>
                            <th class="text-center col-2"># of Not Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($locationList as $list)
                            <tr>
                                <td>{{ $list->NAME }}</td>
                                <td>
                                    @if ($list->LAST_RECORDED)
                                        {{ date('M/d/Y', strtotime($list->LAST_RECORDED)) }}
                                    @endif
                                </td>
                                <td class="text-center">{{ $list->NO_TRANSMIT }}</td>
                                <td class="text-left">{{ $list->DUE }}</td>
                                <td class="text-center">{{ $list->NOT_PAID }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
