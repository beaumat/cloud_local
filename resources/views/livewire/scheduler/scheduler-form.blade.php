<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            {{-- <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Schedules</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Schedulers</li>
                    </ol>
                </div>
            </div> --}}
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Schedules</h4>
                            </div>
                            <div class="card-body">
                                <!-- the events -->
                                <livewire:select-option name="CONTACT_ID" :options="$contactList" :zero="true"
                                    titleName="Patient :" wire:model.live='CONTACT_ID' :key="$contactList->pluck('ID')->join('_')" />
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create Event</h3>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-primary px-4">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar">
                                <div>
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList"
                                                :zero="false" titleName="Location :" :vertical="true"
                                                wire:model.live='LOCATION_ID' :key="$locationList->pluck('ID')->join('_')" />
                                        </div>
                                        <div class="col-md-6 text-center mt-2">
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
                                                << </button>
                                                    <button class="btn btn-primary btn-sm" wire:click.live="todayMonth">
                                                        Today
                                                    </button>
                                                    <button class="btn btn-primary btn-sm" wire:click.live="nextMonth">
                                                        >>
                                                    </button>
                                        </div>
                                    </div>
                                    <livewire:scheduler.calendar :year="$year" :month="$month" :contactid="$CONTACT_ID"
                                        :locationid="$LOCATION_ID" :key="$refreshComponent" />
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
