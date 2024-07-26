<div>
    <button wire:click="openModal" class="btn btn-primary btn-sm text-xs ">
        Print PF
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Doctor Professional Fee</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="mt-0">
                                    <label class="text-sm">Location:</label>
                                    <select name="location" wire:model.live='LOCATION_ID'
                                        class="form-control form-control-sm">
                                        @foreach ($locationList as $item)
                                            <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                            </option>
                                        @endforeach
                                    </select>
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
