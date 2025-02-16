<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Philhealth Agreement Form</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body bg-light">
                            <div class="row">
                                <div class="col-4">
                                    @livewire('ServiceCharge.AgreementFormDetails', ['HEMO_ID' => $HEMO_ID])
                                </div>
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-12">
                                            @livewire('ServiceCharge.AgreementFormItems', ['HEMO_ID' => $HEMO_ID])
                                        </div>

                                        <div class="col-12">
                                            @livewire('ServiceCharge.AgreementFormConforme', ['HEMO_ID' => $HEMO_ID])
                                        </div>
                                        <div class="col-12 text-right">
                                            {{-- close --}}
                                            <a target="_BLANK"
                                                href="{{ route('patientsagreement_form', ['id' => $HEMO_ID]) }}"
                                                class="btn btn-sm btn-info">Print Form</a>
                                            <button type="button" class="btn btn-secondary btn-sm m-1"
                                                wire:click="closeModal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
