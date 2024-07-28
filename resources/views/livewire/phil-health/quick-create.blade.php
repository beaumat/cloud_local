<div>
    <button wire:click="openModal" class="btn btn-warning btn-sm text-xs">
        <i class="fa fa-calendar-plus-o" aria-hidden="true"></i> Quick Create
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Quick Create</div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <livewire:date-input name="DATE_FROM" titleName="Date From"
                                    wire:model.live='DATE_FROM' />
                            </div>
                            <div class="col-md-3">
                                <livewire:date-input name="DATE_TO" titleName="Date To" wire:model.live='DATE_TO' />
                            </div>
                            <div class="col-md-6">
                                <livewire:dropdown-option name="LOCATION_ID" titleName="Location" :options="$locationList"
                                    :zero="false" :isDisabled=false wire:model.live='LOCATION_ID' />
                            </div>

                        </div>
                        <table class="table table-sm table-bordered table-hover mt-2">
                            <thead class="bg-sky text-xs">
                                <tr>
                                    <th class="text-center col-1"> <input type="checkbox" wire:model.live='SelectAll' />
                                    </th>
                                    <th class="col-4">Patient Name</th>
                                    <th class="col-2 text-center">No. of Treatment</th>
                                    <th class="col-2">Philhealth No.</th>

                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox"
                                                wire:model.live='patientSelected.{{ $list->ID }}' />
                                        </td>
                                        <td>{{ $list->PATIENT }}</td>
                                        <td class="text-center">{{ $list->TOTAL_HEMO }}</td>
                                        <td>{{ $list->PIN }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                    <button class="btn btn-primary btn-sm" wire:click='getReload()'>
                                        Reload
                                    </button>
                                </div>

                                <div class="col-6 text-right">
                                    <button type="button" wire:click='create()' class="btn btn-success btn-sm">
                                        Create
                                    </button>
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>
