<div class="card card-sm">
    <div class="pt-1 pb-1 card-header bg-sky">
        <h3 class="card-title">Order Preference</h3>
    </div>

    <div class="card-body">
        <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '' , 'message' => session('message'), 'error' => session('error')])
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <form wire:submit.prevent='saveItem'>
                            <div class="mb-1 row">
                                <div class="col-md-6">
                                    @if ($saveSuccess)
                                        <livewire:select-option name="LOCATION_ID1" titleName="Location"
                                            :options="$locationList" :zero="true" wire:model='LOCATION_ID'
                                            :vertical="false" />
                                    @else
                                        <livewire:select-option name="LOCATION_ID2" titleName="Location"
                                            :options="$locationList" :zero="true" wire:model='LOCATION_ID'
                                            :vertical="false" />
                                    @endif

                                </div>
                                <div class="col-md-3">
                                    <livewire:number-input name="ORDER_POINT" titleName="Order Point"
                                        wire:model='ORDER_POINT' :vertical="false" />
                                </div>
                                <div class="col-md-3">
                                    <livewire:number-input name="ORDER_QTY" titleName="Order Qty" wire:model='ORDER_QTY'
                                        :vertical="false" />
                                </div>

                                <div class="col-md-3">
                                    <livewire:number-input name="ORDER_LEADTIME" titleName="Leadtime"
                                        wire:model='ORDER_LEADTIME' :vertical="false" />
                                </div>

                                <div class="col-md-3">
                                    <livewire:number-input name="ONHAND_MAX_LIMIT" titleName="Max-Limit"
                                        wire:model='ONHAND_MAX_LIMIT' :vertical="false" />
                                </div>
                                <div class="col-md-4">
                                    @if ($saveSuccess)
                                        <livewire:select-option name="STOCK_BIN_ID1" titleName="Stock Bin"
                                            :options="$stockBinList" :zero="true" wire:model='STOCK_BIN_ID'
                                            :vertical="false" />
                                    @else
                                        <livewire:select-option name="STOCK_BIN_ID2" titleName="Stock Bin"
                                            :options="$stockBinList" :zero="true" wire:model='STOCK_BIN_ID'
                                            :vertical="false" />
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <button style="margin-top:40px;" class="text-white btn btn-success btn-sm w-100">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                </div>

                            </div>
                        </form>

                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th>Location</th>
                                    <th class="text-right col-1">Point</th>
                                    <th class="text-right col-1">Quantity</th>
                                    <th class="text-right col-1">Lead-time</th>
                                    <th class="text-right col-1">Max-Limit</th>
                                    <th>Stock Bin</th>
                                    <th class="text-center col-2">Action </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach ($itemPreferenceList as $list)
                                    <tr>
                                        <td> {{ $list->LOCATION_NAME }}</td>
                                        <td class="text-right">
                                            @if ($editItemId === $list->ID)
                                                <input type="number" wire:model="NEW_ORDER_POINT"
                                                    class="form-control form-control-sm text-xs text-right">
                                            @else
                                                {{ number_format($list->ORDER_POINT, 2) }}
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            @if ($editItemId === $list->ID)
                                                <input type="number" wire:model="NEW_ORDER_QTY"
                                                    class="form-control form-control-sm text-xs text-right">
                                            @else
                                                {{ number_format($list->ORDER_QTY, 2) }}
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            @if ($editItemId === $list->ID)
                                                <input type="number" wire:model="NEW_ORDER_LEADTIME"
                                                    class="form-control form-control-sm text-xs text-right">
                                            @else
                                                {{ number_format($list->ORDER_LEADTIME, 0) }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($editItemId === $list->ID)
                                                <input type="number" wire:model="NEW_ONHAND_MAX_LIMIT"
                                                    class="form-control form-control-sm text-xs text-right">
                                            @else
                                                {{ number_format($list->ONHAND_MAX_LIMIT, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($editItemId === $list->ID)
                                                <select wire:model='NEW_STOCK_BIN_ID' id="stock_bin"
                                                    class="text-sm form-control form-control-sm">
                                                    <option value="0"> Choose stock bin </option>
                                                    @foreach ($stockBinList as $option)
                                                        <option value="{{ $option->ID }}">
                                                            {{ $option->DESCRIPTION }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{ $list->STOCK_BIN_NAME }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($editItemId === $list->ID)
                                                <button title="Update" id="updatebtn"
                                                    wire:click="updateItem({{ $list->ID }})"
                                                    class="text-success btn btn-sm btn-link">
                                                    <i class="fas fa-check" aria-hidden="true"></i>
                                                </button>
                                                <button title="Cancel" id="cancelbtn" href="#"
                                                    wire:click="cancelItem()" class="text-warning btn btn-sm btn-link">
                                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                                </button>
                                            @else
                                                <button title="Edit" id="editbtn"
                                                    wire:click='editItem({{ $list->ID . ',' . $list->ORDER_POINT . ',' . $list->ORDER_QTY . ',' . $list->ORDER_LEADTIME . ',' . $list->ONHAND_MAX_LIMIT . ',' . $list->STOCK_BIN_ID }})'
                                                    class="text-info btn btn-sm btn-link">
                                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                                </button>
                                                <button title="Delete" id="deletebtn"
                                                    wire:click='deleteItem({{ $list->ID }})'
                                                    wire:confirm="Are you sure you want to delete this?"
                                                    class="text-danger btn btn-sm btn-link">
                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
