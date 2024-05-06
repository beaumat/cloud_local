<div>

    <button wire:click="openModal" class="btn btn-success btn-sm text-xs ">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Add payment to service charge</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                    <div class="modal-body">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'available') active @endif"
                                            id="custom-tabs-four-available-tab" wire:click="SelectTab('available')"
                                            data-toggle="pill" href="#custom-tabs-four-available" role="tab"
                                            aria-controls="custom-tabs-four-available" aria-selected="true">Available
                                            Payment</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'new') active @endif"
                                            id="custom-tabs-four-new-tab" wire:click="SelectTab('new')"
                                            data-toggle="pill" href="#custom-tabs-four-new" role="tab"
                                            aria-controls="custom-tabs-four-new" aria-selected="true">Make Payment</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'available') show active @endif"
                                        id="custom-tabs-four-available" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-available-tab">
                                        @livewire('ServiceCharge.PaymentModalAvailable', ['PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID])

                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'new') show active @endif"
                                        id="custom-tabs-four-new" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-new-tab">
                                        @livewire('ServiceCharge.PaymentModalNewEntry', ['PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID])
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
