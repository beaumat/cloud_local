<div>

    <button wire:click="openModal" class="btn btn-info btn-sm text-xs ">
        Print List
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Multiple Select </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th></th>
                                        <th class="col-2 text-left">ID No.</th>
                                        <th class="col-2 text-left">Date</th>
                                        <th class="col-8 text-left">Patient Name</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($hemoList as $list)
                                        <tr>
                                            <td class="text-center">
                                                <input class="text-lg" type="checkbox"
                                                    wire:model.live="hemoSelected.{{ $list->ID }}" />
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->CODE }}</label>
                                            </td>
                                            <td class="text-left">
                                                <label for="">
                                                    {{ \Carbon\Carbon::parse($list->DATE)->format('m/d/Y') }}
                                                </label>
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->PATIENT_NAME }}</label>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info btn-sm" wire:click='print'> <i class="fas fa-plus"></i> Print
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

@script
    <script>
        $wire.on('openNewTab', (eventData) => {
            window.open(eventData.data, '_blank');
        });
    </script>
@endscript
