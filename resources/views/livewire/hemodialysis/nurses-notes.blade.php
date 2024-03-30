<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th>TIME</th>
                <th class="1">BP</th>
                <th>HR</th>
                <th>BFR</th>
                <th>AP</th>
                <th>VP</th>
                <th>TFR</th>
                <th>TMP</th>
                <th>HARAPIN</th>
                <th>FLUSHING</th>
                <th class="col-3">NURSES NOTES</th>
                <th class="text-center col-sm-1 col-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($data as $list)
                <tr>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="time" class="form-control" name="TIME" wire:model='EDIT_TIME' />
                        @else
                            {{ $list->TIME }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm text-xs" name="EDIT_BP_1"
                                        wire:model='EDIT_BP_1' />
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control form-control-sm text-xs" name="EDIT_BP_2"
                                        wire:model='EDIT_BP_2' />
                                </div>
                            </div>
                        @else
                            {{ $list->BP_1 }}/{{ $list->BP_2 }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_HR"
                                wire:model='EDIT_HR' />
                        @else
                            {{ $list->HR }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_BFR"
                                wire:model='EDIT_BFR' />
                        @else
                            {{ $list->BFR }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_AP"
                                wire:model='EDIT_AP' />
                        @else
                            {{ $list->AP }}
                        @endif

                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_VP"
                                wire:model='EDIT_VP' />
                        @else
                            {{ $list->VP }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_TFR"
                                wire:model='EDIT_TFR' />
                        @else
                            {{ $list->TFR }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_TMP"
                                wire:model='EDIT_TMP' />
                        @else
                            {{ $list->TMP }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_HEPARIN"
                                wire:model='EDIT_HEPARIN' />
                        @else
                            {{ $list->HEPARIN }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="number" class="form-control form-control-sm" name="EDIT_FLUSHING"
                                wire:model='EDIT_FLUSHING' />
                        @else
                            {{ $list->FLUSHING }}
                        @endif
                    </td>
                    <td>
                        @if ($EDIT_ID == $list->ID)
                            <input type="text" class="form-control form-control-sm" name="EDIT_NOTES"
                                wire:model='EDIT_NOTES' />
                        @else
                            {{ $list->NOTES }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($EDIT_ID == $list->ID)
                            <button wire:click='updateData()' name="UPDATE" title="Update"
                                class="btn btn-primary btn-sm">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
                            <button wire:click='cancel()' name="CANCLED" title="Cancel" class="btn btn-warning btn-sm">
                                <i class="fa fa-ban" aria-hidden="true"></i>
                            </button>
                        @else
                            <button
                                wire:click="editData({{ $list->ID }}, '{{ $list->TIME }}', {{ $list->BP_1 }}, {{ $list->BP_2 }}, {{ $list->HR }}, {{ $list->BFR }}, {{ $list->AP }}, {{ $list->VP }}, {{ $list->TFR }}, {{ $list->TMP }}, {{ $list->HEPARIN }}, {{ $list->FLUSHING }}, '{{ $list->NOTES }}')"
                                name="EDIT" title="Edit" class="btn btn-info btn-sm"><i class="fa fa-pencil"
                                    aria-hidden="true"></i></button>
                            <button wire:click='delete({{ $list->ID }})' name="DELETE" title="Delete"
                                class="btn btn-danger btn-sm"> <i class="fa fa-minus-circle"
                                    aria-hidden="true"></i></button>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr>
                <form id="quickForm" wire:submit.prevent='save'>
                    <td><input type="time" class="form-control" name="TIME" wire:model='TIME' /> </td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" class="form-control form-control-sm" name="BP_1"
                                    wire:model='BP_1' />
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control form-control-sm" name="BP_2"
                                    wire:model='BP_2' />
                            </div>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="HR" wire:model='HR' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="BFR"
                            wire:model='BFR' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="AP" wire:model='AP' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="VP" wire:model='VP' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="TFR"
                            wire:model='TFR' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="TMP"
                            wire:model='TMP' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="HEPARIN"
                            wire:model='HEPARIN' />
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm" name="FLUSHING"
                            wire:model='FLUSHING' />
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="NOTES"
                            wire:model='NOTES' />
                    </td>
                    <td class="text-center">
                        <button type="submit" class="btn btn-success btn-sm w-50">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
