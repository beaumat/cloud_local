<div class="form-group">
    <div class='row'>
        <div class="col-md-12">
            <table class="table table-sm table-bordered table-hover">
                <thead class="text-xs bg-sky">
                    <tr>
                        <th class="col-1">TIME</th>
                        <th class="text-center col-3">BP</th>
                        <th class="text-center">HR</th>
                        <th class="text-center">BFR</th>
                        <th class="col-3 text-center">AP | VP</th>
                        <th class="text-center">TFP</th>
                        <th class="text-center">TMP</th>
                        <th class="text-center">HEPARIN</th>
                        <th class="text-center">FLUSHING</th>
                        <th class="col-4 text-center">NURSES NOTES</th>
                        <th class="col-2 text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody class='text-xs'>
                    @foreach ($dataList as $list)
                        <tr>
                            <td class='text-center'>
                                @if ($list->ID == $EDIT_ID)
                                    <input type="time" name="TIME" class="text-xs w-100" wire:model='EDIT_TIME' />
                                @else
                                    {{ $list->TIME }}
                                @endif
                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_BP_1" class="text-xs w-50"
                                        wire:model='EDIT_BP_1' /><input type="text" name="EDIT_BP_2" class="text-xs w-50"
                                        wire:model='EDIT_BP_2' />
                                @else
                                    {{ $list->BP_1 }}/{{ $list->BP_2 }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_HR" class="text-xs" wire:model='EDIT_HR' />
                                @else
                                    {{ $list->HR }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_BFR" class="text-xs" wire:model='EDIT_BFR' />
                                @else
                                    {{ $list->BFR }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_AP" class="text-xs w-50" wire:model='EDIT_AP' /><input type="text" name="EDIT_VP" class="text-xs w-50" wire:model='EDIT_VP' />
                                @else
                                    {{ $list->AP }}|{{ $list->VP }}
                                @endif

                            </td>
                            <td class='text-center'>
                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_TFP" class="text-xs" wire:model='EDIT_TFP' />
                                @else
                                    {{ $list->TFP }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_TMP" class="text-xs" wire:model='EDIT_TMP' />
                                @else
                                    {{ $list->TMP }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_HEPARIN" class="text-xs w-100"
                                        wire:model='EDIT_HEPARIN' />
                                @else
                                    {{ $list->HEPARIN }}
                                @endif

                            </td>
                            <td class='text-center'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_FLUSHING" class="text-xs w-100"
                                        wire:model='EDIT_FLUSHING' />
                                @else
                                    {{ $list->FLUSHING }}
                                @endif

                            </td>
                            <td class='text-left'>

                                @if ($list->ID == $EDIT_ID)
                                    <input type="text" name="EDIT_NOTES" class="text-xs w-100" wire:model='EDIT_NOTES' />
                                @else
                                    {{ $list->NOTES }}
                                @endif

                            </td>
                            <td class='text-center'>
                                @if ($list->ID === $EDIT_ID)
                                    <button type="button" name="update" class="btn btn-xs btn-success"
                                        wire:click="update()">
                                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" name="cancel" class="btn btn-xs btn-warning"
                                        wire:click="cancel()">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                    </button>
                                @elseif($EDIT_ID == null)
                                    <button type="button" name="edit" class="btn btn-xs btn-info"
                                        wire:click="edit({{ $list->ID }})">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </button>

                                    <button type="button" name="delete" class="btn btn-xs btn-danger"
                                        wire:click="delete({{ $list->ID }})">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                    {{-- INSERT --}}
                    <form wire:submit.prevent='save()' wire:loading.attr='disabled'>
                        <tr>
                            <td><input type="time" name="TIME" class="text-xs w-100" wire:model='TIME' /></td>
                            <td class="text-center">
                                <input type="text" name="BP_1" class="text-xs w-50" wire:model='BP_1' /><input type="text" name="BP_2" class="text-xs w-50" wire:model='BP_2' />
                            </td>
                            <td><input type="text" name="HR" class="text-xs" wire:model='HR' /></td>
                            <td><input type="text" name="BFR" class="text-xs" wire:model='BFR' /></td>
                            <td>
                                <input type="text" name="AP" class="text-xs w-50" wire:model='AP' /><input type="text" name="VP" class="text-xs w-50" wire:model='VP' />
                            </td>
                            <td><input type="text" name="TFP" class="text-xs" wire:model='TFP' /></td>
                            <td><input type="text" name="TMP" class="text-xs" wire:model='TMP' /></td>
                            <td><input type="text" name="HEPARIN" class="text-xs w-100" wire:model='HEPARIN' />
                            </td>
                            <td><input type="text" name="FLUSHING" class="text-xs w-100" wire:model='FLUSHING' />
                            </td>
                            <td><input type="text" name="NOTES" class="text-xs w-100" wire:model='NOTES' />
                            </td>
                            <td><button type="submit" class="btn btn-xs btn-success w-100"><i class="fa fa-plus"
                                        aria-hidden="true"></i></button></td>
                        </tr>

                    </form>

                </tbody>
            </table>
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

        </div>
    </div>
</div>
