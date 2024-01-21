<div class="card bg-light">
    <h5 class="px-2 card-title">Location Default </h5>
    <div class="card-body">
        <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <div class="text-sm alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session()->has('message'))
                        <div class="pt-1 pb-1 text-sm alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="pt-1 pb-1 text-sm alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="mb-1 row">
                <div class="col-md-4">
                    @if ($saveSuccess)
                        <livewire:select-option name="Unit_LOCATION_ID1" titleName="Location" :options="$locationList"
                            :zero="true" wire:model='LOCATION_ID' :vertical="false" />
                    @else
                        <livewire:select-option name="Unit_LOCATION_ID2" titleName="Location" :options="$locationList"
                            :zero="true" wire:model='LOCATION_ID' :vertical="false" />
                    @endif
                </div>
                <div class="col-md-2">
                    @if ($saveSuccess)
                        <livewire:select-option name="PURCHASES_UNIT_ID1" titleName="Purchases Unit" :options="$unitList"
                            :zero="true" wire:model='PURCHASES_UNIT_ID' :vertical="false" />
                    @else
                        <livewire:select-option name="PURCHASES_UNIT_ID2" titleName="Purchases Unit" :options="$unitList"
                            :zero="true" wire:model='PURCHASES_UNIT_ID' :vertical="false" />
                    @endif
                </div>
                <div class="col-md-2">
                    @if ($saveSuccess)
                        <livewire:select-option name="SALES_UNIT_ID1" titleName="Sales Unit" :options="$unitList"
                            :zero="true" wire:model='SALES_UNIT_ID' :vertical="false" />
                    @else
                        <livewire:select-option name="SALES_UNIT_ID2" titleName="Sales Unit" :options="$unitList"
                            :zero="true" wire:model='SALES_UNIT_ID' :vertical="false" />
                    @endif
                </div>
                <div class="col-md-2">
                    @if ($saveSuccess)
                        <livewire:select-option name="SHIPPING_UNIT_ID1" titleName="Shipping Unit" :options="$unitList"
                            :zero="true" wire:model='SHIPPING_UNIT_ID' :vertical="false" />
                    @else
                        <livewire:select-option name="SHIPPING_UNIT_ID2" titleName="Shipping Unit" :options="$unitList"
                            :zero="true" wire:model='SHIPPING_UNIT_ID' :vertical="false" />
                    @endif
                </div>
                <div class="text-right col-md-2">
                    <button type="submit" wire:loading.attr='hidden' class="text-white btn bg-light-blue btn-sm w-100" style="margin-top: 40px;">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div wire:loading.delay>
                        <span class="spinner"></span>
                    </div>
                </div>
            </div>
        </form>
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-light-blue">
                <tr>
                    <th>Location</th>
                    <th class="text-right col-2">Purchases Unit</th>
                    <th class="text-right col-2">Sales Unit</th>
                    <th class="text-right col-2">Shipping Unit</th>
                    <th class="text-center col-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach ($unitLocationList as $list)
                    <tr>
                        <td>{{ $list->LOCATION_NAME }}</td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newPURCHASES_UNIT_ID' id="newPO_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose purchases unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->PURCHASES_UNIT }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newSALES_UNIT_ID' id="newSALE_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose sales unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->SALES_UNIT }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newSHIPPING_UNIT_ID' id="newSHIP_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose shipping unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->SHIPPING_UNIT }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($editItemId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateItem({{ $list->ID }})"
                                    class="text-success btn btn-sm btn-link">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                    class="text-warning btn btn-sm btn-link">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn"
                                    wire:click='editItem({{ $list->ID . ',' . $list->PURCHASES_UNIT_ID . ',' . $list->SALES_UNIT_ID . ',' . $list->SHIPPING_UNIT_ID }})'
                                    class="text-info btn btn-sm btn-link">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='deleteItem({{ $list->ID }})'
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
