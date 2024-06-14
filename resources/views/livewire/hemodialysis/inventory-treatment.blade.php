<section class="content">
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title text-sm"> Inventory</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button> --}}
            </div>
        </div>
        <div class="card-body p-2">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
            <div class="row" @if ($HEMO_ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                <div class="col-md-12">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky">
                            <tr>
                                <th class="col-2">Code</th>
                                <th>Description</th>
                                <th class="col-1 text-center">Quantity</th>
                                <th class="col-1">Unit</th>
                                <th class="col-1 text-center">New</th>
                                <th class="col-2">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                <tr>
                                    <td>{{ $list->CODE }}</td>
                                    <td>{{ $list->DESCRIPTION }}</td>
                                    <td class="text-center">{{ number_format($list->QUANTITY, 0) }}</td>
                                    <td></td>
                                    <td class="text-center">
                                        @if ($list->IS_NEW)
                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <button title="Delete" id="deletebtn"
                                            wire:click='deleteItem({{ $list->ID }}, {{ $list->ITEM_ID }})'
                                            wire:confirm="Are you sure you want to delete this?"
                                            class="text-danger btn btn-danger btn-sm w-100">
                                            <i class="fas fa-times text-white" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($STATUS == $openStatus)
                                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                                    <tr>
                                        <td class="text-md">
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
                                                class="text-sm form-control form-control-sm mt-2"
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
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    @if ($STATUS == $openStatus)
                        <livewire:custom-check-box name="codeBase" titleName="Use choose item code"
                            wire:model.live='codeBase' />
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    @livewire('Hemodialysis.AddDefaultModal', ['HEMO_ID' => $HEMO_ID, 'LOCATION_ID' => $LOCATION_ID])
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</section>
