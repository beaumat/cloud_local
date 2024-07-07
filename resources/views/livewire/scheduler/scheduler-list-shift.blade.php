<div>
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's1st') active @endif" id="custom-tabs-four-s1st-tab"
                        wire:click="SelectTab('s1st')" data-toggle="pill" href="#custom-tabs-four-s1st" role="tab"
                        aria-controls="custom-tabs-four-s1st" aria-selected="true">1st shift</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's2nd') active @endif" id="custom-tabs-four-s2nd-tab"
                        wire:click="SelectTab('s2nd')" data-toggle="pill" href="#custom-tabs-four-s2nd" role="tab"
                        aria-controls="custom-tabs-four-s2nd" aria-selected="true">2nd shift</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($tab == 's3rd') active @endif" id="custom-tabs-four-s3rd-tab"
                        wire:click="SelectTab('s3rd')" data-toggle="pill" href="#custom-tabs-four-s3rd" role="tab"
                        aria-controls="custom-tabs-four-s3rd" aria-selected="true">3rd shift</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade @if ($tab == 's1st') show active @endif"
                    id="custom-tabs-four-s1st" role="tabpanel" aria-labelledby="custom-tabs-four-s1st-tab">
                    @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 1, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                </div>
                <div class="tab-pane fade @if ($tab == 's2nd') show active @endif"
                    id="custom-tabs-four-s2nd" role="tabpanel" aria-labelledby="custom-tabs-four-s2nd-tab">
                    @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 2, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                </div>
                <div class="tab-pane fade @if ($tab == 's3rd') show active @endif"
                    id="custom-tabs-four-s3rd" role="tabpanel" aria-labelledby="custom-tabs-four-s3rd-tab">
                    @livewire('Scheduler.SchedulerListShiftDetails', ['SHIFT_ID' => 3, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                </div>
            </div>
        </div>
    </div>

    {{-- <table class="table table-sm mt-2">
        <thead class="text-xs bg-sky">
            <tr>
                <th>Shift</th>
                <th>Patient</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="text-xs">

            @foreach ($schedContact as $list)
                <tr>
                    <td>{{ $list->SHIFT }}</td>
                    <td>{{ $list->CONTACT_NAME }}</td>
                    <td>{{ $list->STATUS }}</td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}

</div>
