<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('transactionsschedules') }}"> Schedules Setup</a></h5>
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
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <h4 class="card-title">{{ __('Schedules Setup') }}</h4> --}}
                                    </div>
                                    <div class="col-md-6 text-right">
                                            
                                        <a href="{{ route('transactionsschedules') }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-table" aria-hidden="true"></i>
                                            List</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <livewire:select-option name="CONTACT_ID" :options="$contactList" :zero="true"
                                            titleName="Patient :" wire:model.live='CONTACT_ID' :key="$contactList->pluck('ID')->join('_')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <button class="btn btn-sm btn-info"
                                            @if ($CONTACT_ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                            Generate Schedule
                                        </button> --}}
                                    </div>
                                    <div class="col-md-6 text-right">
                                        {{-- <button class="btn btn-sm btn-warning"
                                            @if ($CONTACT_ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <i class="fa fa-print" aria-hidden="true"></i> Print</button> --}}

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm text-xs">
                                    <thead class="bg-sky text-xs">
                                        <tr>
                                            <th>Date</th>
                                            <th>Shift</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scheduleList as $list)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($list->SCHED_DATE)->format('m/d/Y') }}</td>
                                                <td>{{ $list->SHIFT }}</td>
                                                <td>{{ $list->STATUS }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-primary">
                        <div class="card-body">
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

                                    <livewire:scheduler.calendar :year="$year" :month="$month" :contactid="$CONTACT_ID"
                                        :locationid="$LOCATION_ID" :hemomachineid="$HEMO_MACHINE_ID" :key="$refreshComponent" />
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
