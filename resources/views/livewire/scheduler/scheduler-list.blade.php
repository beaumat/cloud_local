<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('patientsschedules') }}"> Schedules</a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @livewire('Scheduler.PrintSchedules', ['MONTH' => $month, 'YEAR' => $year, 'LOCATION_ID' => $LOCATION_ID])
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-12">
                                        @can('patient.schedule.modify')
                                            <a href="{{ route('patientsschedules_setup') }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-cog" aria-hidden="true"></i> Setup</a>
                                        @endcan
                                        @can('patient.schedule.print')
                                            <button wire:click="openModalPrint" class="btn btn-primary btn-sm">
                                                <i class="fa fa-print" aria-hidden="true"></i> Preview
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <h5 class="text-primary card-title">Date on <b class="text-success">
                                                @if ($schedContact && count($schedContact) > 0)
                                                    {{ $DATE->format('m/d/Y') }}
                                                @endif
                                            </b>
                                        </h5>
                                    </div>



                                    <div class="col-md-6 text-right">
                                        {{-- <button class="btn btn-sm btn-warning text-xs">
                                            <i class="fa fa-print" aria-hidden="true"></i> Print
                                        </button> --}}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    {{-- <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link @if ($tab == 's1st') active @endif"
                                                        id="custom-tabs-four-s1st-tab" wire:click="SelectTab('s1st')"
                                                        data-toggle="pill" href="#custom-tabs-four-s1st" role="tab"
                                                        aria-controls="custom-tabs-four-s1st"
                                                        aria-selected="true">1st</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link @if ($tab == 's2nd') active @endif"
                                                        id="custom-tabs-four-s2nd-tab" wire:click="SelectTab('s2nd')"
                                                        data-toggle="pill" href="#custom-tabs-four-s2nd" role="tab"
                                                        aria-controls="custom-tabs-four-s2nd"
                                                        aria-selected="true">2nd</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link @if ($tab == 's3rd') active @endif"
                                                        id="custom-tabs-four-s3rd-tab" wire:click="SelectTab('s3rd')"
                                                        data-toggle="pill" href="#custom-tabs-four-s3rd" role="tab"
                                                        aria-controls="custom-tabs-four-s3rd"
                                                        aria-selected="true">3rd</a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="card-body">

                                        </div>
                                    </div>
                                    <table class="table table-sm mt-2">
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

                                    @livewire('Scheduler.SchedulerListShift', ['LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE->format('Y-m-d')])
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-body bg-white">
                            <!-- THE CALENDAR -->
                            <div id="calendar">
                                <div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList"
                                                :zero="false" titleName="Location" :vertical="true"
                                                wire:model.live='LOCATION_ID' />
                                        </div>
                                        <div class="col-md-5 text-center mt-2">
                                            <h5>
                                                <select wire:model.live='month'>
                                                    @foreach ($monthList as $list)
                                                        <option value="{{ $list['ID'] }}"> {{ $list['NAME'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                : <input type="number" wire:model.live.debounce='year'
                                                    style="width: 100px;" />
                                            </h5>
                                        </div>
                                        <div class="col-md-3 text-right mt-2">
                                            <button class="btn btn-primary btn-sm" wire:click.live="previousMonth">
                                                <i class="fa fa-chevron-circle-left" aria-hidden="true"></i> </button>
                                            <button class="btn btn-primary btn-sm" wire:click.live="todayMonth">
                                                Today
                                            </button>
                                            <button class="btn btn-primary btn-sm" wire:click.live="nextMonth">
                                                <i class="fa fa-chevron-circle-right" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <livewire:scheduler.calendar-list :year="$year" :month="$month"
                                        :locationid="$LOCATION_ID" :key="$refreshComponent" :date="$DATE" />
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
