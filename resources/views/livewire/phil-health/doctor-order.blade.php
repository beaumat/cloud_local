<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <span wire:confirm='Are u sure?' wire:click='AutoSetDefault()'>
                            Doctor
                            Order/Action
                        </span>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        {{-- <textarea class="form-control form-control-sm" rows='6' wire:model='DOCTOR_ORDER'></textarea> --}}
                        <table class="table table-bordered table-hover">
                            <thead class="text-xs">
                                <tr>
                                    <th class="col-9">Doctor Order</th>
                                    <th class="col-3 text-center">Control</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $item)
                                    <tr>
                                        <td>
                                            @if ($editID == $item->ID)
                                                <input class="form-control form-control-sm w-100"
                                                    wire:model='DOCTOR_ORDER' />
                                            @else
                                                {{ $item->DESCRIPTION }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($editID == $item->ID)
                                                <button wire:click='save()' class="btn btn-xs btn-success">Save</button>
                                                <button wire:click='cancel()'
                                                    class="btn btn-xs btn-warning">Cancel</button>
                                            @else
                                                <button wire:click='edit({{ $item->ID }})'
                                                    class="btn btn-xs btn-info">Edit</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-xs">
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
