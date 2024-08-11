<section class="content">
    <!-- Default box -->
    <div class="card" @if ($STATUS == 2 || $STATUS == 3) style="opacity: 0.5;pointer-events: none;" @endif>
        <div class="card-body p-2">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
            <div class="row" @if ($HEMO_ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                @if ($ActiveRequired)
                    @if ($STATUS == $openStatus)
                        <div class="col-md-12">
                            @foreach ($ItemRequiredList as $list)
                                <button wire:click='addItem({{ $list->ID }})' class="btn btn-warning btn-md m-1">
                                    {{ $list->ITEM_NAME }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif
                <div class="col-md-12">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-info ">
                            <tr>
                                <th>Code</th>
                                <th class="col-4">Description</th>
                                <th class="col-2 text-center">Qty</th>
                                <th class="text-center">Unit</th>
                                <th class="col-1 text-center">New</th>
                                @if ($STATUS == $openStatus)
                                    <th class="col-2 text-center">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>
                                        @if ($list->NO_OF_USED > 1)
                                            <a wire:click='OpenUsageHistory({{ $list->ITEM_ID }})' href="#"
                                                class="font-weight-bold text-info">
                                                {{ $list->CODE }}
                                            </a>
                                        @else
                                            {{ $list->CODE }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($list->NO_OF_USED > 1)
                                            <a wire:click='OpenUsageHistory({{ $list->ITEM_ID }})' href="#"
                                                class="font-weight-bold text-info">
                                                {{ $list->DESCRIPTION }}
                                            </a>
                                        @else
                                            {{ $list->DESCRIPTION }}
                                        @endif

                                    </td>
                                    <td class="text-center">
                                        @if ($lineId == $list->ID)
                                            <input type="number" step="0.01" class="w-100 text-xs text-right"
                                                name="lineQty" wire:model='lineQty' />
                                        @else
                                            @if ($list->NO_OF_USED > 1)
                                                <a wire:click='OpenUsageHistory({{ $list->ITEM_ID }})' href="#"
                                                    class="font-weight-bold text-info">
                                                    {{ number_format($list->QUANTITY, 0) }}
                                                </a>
                                            @else
                                                {{ number_format($list->QUANTITY, 0) }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($lineId == $list->ID)
                                            <select wire:model='lineUnitId' name="lineUnitId" class="text-sm w-100">
                                                @foreach ($editUnitList as $listitem)
                                                    <option value="{{ $listitem->ID }}">{{ $listitem->SYMBOL }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            {{ $list->SYMBOL }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($lineId == $list->ID)
                                            <input type="checkbox" class="text-xs mt-2" wire:model='lineIsNew'
                                                name="lineIsNew" />
                                        @else
                                            @if ($list->IS_NEW)
                                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($STATUS == $openStatus)
                                        <td class="text-center">
                                            @if ($lineId == $list->ID)
                                                <button type="button" title="Update" id="updatebtn"
                                                    wire:click="updateItem()" class="btn btn-xs btn-success">
                                                    <i class="fas fa-check" aria-hidden="true"></i>
                                                </button>
                                                <button type="button" title="Cancel" id="cancelbtn" href="#"
                                                    wire:click="cancelItem()" class="btn btn-xs btn-warning">
                                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                @if ($CAN_BE_EDIT)
                                                    <button type="button" title="Edit" id="editbtn"
                                                        wire:click='editItem({{ $list->ID }})'
                                                        class="btn btn-xs btn-primary">
                                                        <i class="fas fa-edit " aria-hidden="true"></i>
                                                    </button>
                                                @endif

                                                @if ($list->IS_DEFAULT)
                                                    <button type="button" title="Delete" id="deletebtn"
                                                        wire:click='deleteItem({{ $list->ID }}, {{ $list->ITEM_ID }}, {{ $list->UNIT_ID }})'
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash " aria-hidden="true"></i>
                                                    </button>
                                                @else
                                                    <button type="button" title="Delete" id="deletebtn"
                                                        class="btn btn-xs btn-secondary"> <i class="fas fa-trash "
                                                            aria-hidden="true"></i></button>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            {{-- @if ($STATUS == $openStatus)
                                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                                    <tr>
                                        <td class="text-xs">
                                            @if ($saveSuccess)
                                                @if ($codeBase)
                                                    <livewire:select-option name="ITEM_ID1" titleName="Item Code"
                                                        :options="$itemCodeList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" :withLabel="false" />
                                                @else
                                                    <label class="mt-2 text-xs"> {{ $ITEM_CODE }}</label>
                                                @endif
                                            @else
                                                @if ($codeBase)
                                                    <livewire:select-option name="ITEM_ID2" titleName="Item Code"
                                                        :options="$itemCodeList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" :withLabel="false" />
                                                @else
                                                    <label class="mt-2 text-xs"> {{ $ITEM_CODE }}</label>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-md">
                                            @if ($saveSuccess)
                                                @if (!$codeBase)
                                                    <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" :withLabel="false" />
                                                @else
                                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                                @endif
                                            @else
                                                @if (!$codeBase)
                                                    <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" :withLabel="false" />
                                                @else
                                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" step="0.01"
                                                class="form-control form-control-sm mt-2 text-right" name="Qty"
                                                wire:model.live.debounce.1000ms='QUANTITY'
                                                @if ($ITEM_ID == 0) readonly @endif />
                                        </td>
                                        <td>
                                            <select wire:model='UNIT_ID' name="UNIT_ID"
                                                class="form-control form-control-sm mt-2"
                                                @if ($ITEM_ID == 0) readonly @endif>
                                                @foreach ($unitList as $list)
                                                    <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" class="text-lg mt-2" wire:model='IS_NEW'
                                                name="isNew" @if ($ITEM_ID == 0) disabled @endif />
                                        </td>
                                        <td>
                                            <div class="mt-2">
                                                <button type="submit" wire:loading.attr='hidden'
                                                    @if ($ITEM_ID == 0) disabled @endif
                                                    class="text-white btn bg-sky btn-sm w-100">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <div wire:loading.delay>
                                                    <span class="spinner"></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            @endif --}}
                        </tbody>
                    </table>
                </div>
                {{-- <div class="col-md-6">
                    @if ($STATUS == $openStatus)
                        <livewire:custom-check-box name="codeBase" titleName="Use item code"
                            wire:model.live='codeBase' />
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    @if ($STATUS == $openStatus)
                        @livewire('Hemodialysis.AddDefaultModal', ['HEMO_ID' => $HEMO_ID, 'LOCATION_ID' => $LOCATION_ID])
                    @endif
                </div> --}}
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    @livewire('Hemodialysis.ModalUsage')
</section>
