<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceothersitem_treatment') }}"> Item Treatment </a>
                    </h5>
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
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' =>
                session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card card-sm">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title"> {{ $ID === 0 ? 'Create' : 'Edit' }}</h3>
                        </div>
                        <form id="quickForm" wire:submit.prevent="save">
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <livewire:select-option name="LOCATION_ID" :options="$locationList"
                                                :zero="true" titleName="Location" wire:model='LOCATION_ID' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:select-option name="ITEM_ID" :options="$itemList" :zero="true"
                                                titleName="Item Name" wire:model='ITEM_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="QUANTITY" titleName="Default Qty"
                                                wire:model='QUANTITY' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:select-option name="UNIT_ID" :options="$unitList" :zero="true"
                                                titleName="Unit" wire:model='UNIT_ID' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:number-input name="NO_OF_USED" titleName="No. of Used"
                                                wire:model='NO_OF_USED' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:custom-check-box name="INACTIVE" titleName="Inactive"
                                                wire:model='INACTIVE' />
                                        </div>
                                        <div class="col-md-2">
                                            <livewire:custom-check-box name="IS_AUTO" titleName="Is Auto"
                                                wire:model='IS_AUTO' />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            {{ $ID === 0 ? 'Save' : 'Update' }}
                                        </button>
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($ID > 0)
                                        <a id="new" title="Create"
                                            href="{{ route('maintenanceothersitem_treatment_create') }}"
                                            class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i></a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
</div>