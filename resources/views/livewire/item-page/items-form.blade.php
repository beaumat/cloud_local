<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <h3 class="card-title">
                                {{ $ID == 0 ? 'Create' : '' }}
                                <a class="text-light" href="{{ route('maintenanceinventoryitem') }}"> Items </a>
                            </h3>
                        </div>
                        <div class="container-fluid">
                            <div class="card card-tabs mt-2 ">
                                <div class="card-header p-0 pt-1 border-bottom-0">
                                    <ul class="nav nav-tabs text-sm" id="custom-tabs-three-tab" role="tablist">
                                        <li class="nav-item">
                                            <a wire:click="SelectTab('gen')"
                                                class="nav-link @if ($activeTab == 'gen') active @endif"
                                                id="custom-tabs-three-gen1-tab" data-toggle="pill"
                                                href="#custom-tabs-three-gen1" role="tab"
                                                aria-controls="custom-tabs-three-gen1" aria-selected="true">
                                                General Infomation
                                            </a>
                                        </li>
                                        @if ($TYPE == 1 || $TYPE == 6)
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('other')"
                                                    class="nav-link @if ($activeTab == 'other') active @endif"
                                                    id="custom-tabs-three-other-tab" data-toggle="pill"
                                                    href="#custom-tabs-three-other" role="tab"
                                                    aria-controls="custom-tabs-three-other" aria-selected="false">
                                                    @if ($TYPE == 1)
                                                        Components
                                                    @elseif ($TYPE == 6)
                                                        Group
                                                    @endif

                                                </a>
                                            </li>
                                        @endif

                                        @if ($TYPE < 2)
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('inv')"
                                                    class="nav-link @if ($activeTab == 'inv') active @endif"
                                                    id="custom-tabs-three-inventory-tab" data-toggle="pill"
                                                    href="#custom-tabs-three-inventory" role="tab"
                                                    aria-controls="custom-tabs-three-inventory"
                                                    aria-selected="false">Inventory</a>
                                            </li>

                                            <li class="nav-item">
                                                <a wire:click="SelectTab('unit')"
                                                    class="nav-link @if ($activeTab == 'unit') active @endif"
                                                    id="custom-tabs-three-unit-tab" data-toggle="pill"
                                                    href="#custom-tabs-three-unit" role="tab"
                                                    aria-controls="custom-tabs-three-unit"
                                                    aria-selected="false">Units</a>
                                            </li>
                                        @endif
                                        @if ($TYPE < 5)
                                            <li class="nav-item">
                                                <a wire:click="SelectTab('plv')"
                                                    class="nav-link @if ($activeTab == 'plv') active @endif"
                                                    id="custom-tabs-three-pricelevel-tab" data-toggle="pill"
                                                    href="#custom-tabs-three-pricelevel" role="tab"
                                                    aria-controls="custom-tabs-three-pricelevel"
                                                    aria-selected="false">Price Levels</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content text-sm" id="custom-tabs-three-tabContent">
                                        <div class="tab-pane fade @if ($activeTab == 'gen') show active @endif"
                                            id="custom-tabs-three-gen1" role="tabpanel"
                                            aria-labelledby="custom-tabs-three-gen1-tab">
                                            {{-- gen start --}}
                                            <form id="quickForm" wire:submit.prevent='save'
                                                wire:loading.attr='disabled'>
                                                <div class="card-body">
                                                    <div class="card bg-light">
                                                        <div class="card-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6"
                                                                        @if ($ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                                        <livewire:select-option name="TYPE"
                                                                            :options="$itemType" titleName="Type"
                                                                            :zero="false" wire:model.live='TYPE'
                                                                            :vertical="true" />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <livewire:text-input name="CODE"
                                                                            titleName="Code" wire:model='CODE'
                                                                            :vertical="true" :withLabel="true" />
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <livewire:text-input name="DESCRIPTION"
                                                                            titleName="Description"
                                                                            wire:model='DESCRIPTION' :vertical="true"
                                                                            maxlength='50'>
                                                                    </div>

                                                                    @if ($TYPE === 0)
                                                                        <div class="col-md-6">
                                                                            <livewire:text-input
                                                                                name="PURCHASE_DESCRIPTION"
                                                                                titleName="Purchase Description"
                                                                                wire:model='PURCHASE_DESCRIPTION'
                                                                                :vertical="true" maxlength='50'>
                                                                        </div>
                                                                    @endif

                                                                    @if ($TYPE === 6)
                                                                        <div class="col-md-6">
                                                                            <livewire:custom-check-box
                                                                                name="PRINT_INDIVIDUAL_ITEMS"
                                                                                titleName="Print Individual Items"
                                                                                wire:model='PRINT_INDIVIDUAL_ITEMS' />
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <div class="row">
                                                                    @if ($TYPE <= 4 || $TYPE === 7)
                                                                        @if ($TYPE < 2)
                                                                            <div class="col-md-6">
                                                                                <div class="row">
                                                                                    @if ($TYPE === 0)
                                                                                        <div class="col-md-12">
                                                                                            <livewire:number-input
                                                                                                name="COST"
                                                                                                titleName="Cost"
                                                                                                wire:model='COST'
                                                                                                :vertical="true" />
                                                                                        </div>
                                                                                    @endif
                                                                                    <div class="col-md-12">
                                                                                        <livewire:select-option
                                                                                            name="GROUP_ID"
                                                                                            :options="$itemGroup"
                                                                                            :zero="true"
                                                                                            titleName="Item Group"
                                                                                            wire:model='GROUP_ID'
                                                                                            :vertical="true" />
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <livewire:select-option
                                                                                            name="STOCK_TYPE"
                                                                                            :options="$stockType"
                                                                                            :zero="true"
                                                                                            titleName="Stock Type"
                                                                                            wire:model='STOCK_TYPE'
                                                                                            :vertical="true" />
                                                                                    </div>

                                                                                    @if ($TYPE == 0)
                                                                                        <div class="col-md-12">
                                                                                            <livewire:select-option
                                                                                                name="PREFERRED_VENDOR_ID"
                                                                                                :options="$vendors"
                                                                                                :zero="true"
                                                                                                titleName="Preferred Vendor"
                                                                                                wire:model='PREFERRED_VENDOR_ID'
                                                                                                :vertical="true" />
                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <livewire:select-option
                                                                                                name="MANUFACTURER_ID"
                                                                                                :options="$manufacturers"
                                                                                                :zero="true"
                                                                                                wire:model='MANUFACTURER_ID'
                                                                                                titleName="Manufacturer"
                                                                                                :vertical="true" />
                                                                                        </div>
                                                                                    @endif
                                                                                    @if ($TYPE <= 1)
                                                                                        <div class="col-md-12">
                                                                                            <livewire:select-option
                                                                                                name="COGS_ACCOUNT_ID"
                                                                                                :options="$accounts"
                                                                                                :zero="true"
                                                                                                titleName="COGS Accounts"
                                                                                                wire:model='COGS_ACCOUNT_ID'
                                                                                                :vertical="true" />
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if ($TYPE <= 4 || $TYPE == 7 || $TYPE == 6)
                                                                        <div class="col-md-6">
                                                                            <div class="row">
                                                                                @if ($TYPE != 6)
                                                                                    <div class="col-md-12">
                                                                                        <livewire:number-input
                                                                                            name="RATE"
                                                                                            titleName="Rate"
                                                                                            wire:model='RATE'
                                                                                            :vertical="true" />
                                                                                    </div>
                                                                                @endif

                                                                                @if ($TYPE < 4 || $TYPE == 6)
                                                                                    <div class="col-md-12">
                                                                                        <livewire:select-option
                                                                                            name="CLASS_ID"
                                                                                            titleName="Item Class"
                                                                                            :options="$itemClass"
                                                                                            :zero="true"
                                                                                            wire:model.live='CLASS_ID'
                                                                                            :key="$itemClass
                                                                                                ->pluck('ID')
                                                                                                ->join('_')"
                                                                                            :vertical="true" />
                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        @if ($itemSubClass)
                                                                                            <livewire:select-option
                                                                                                name="SUB_CLASS_ID"
                                                                                                titleName="Item Sub-class"
                                                                                                :options="$itemSubClass"
                                                                                                :zero="true"
                                                                                                wire:model='SUB_CLASS_ID'
                                                                                                :key="$itemSubClass
                                                                                                    ->pluck('ID')
                                                                                                    ->join('_')"
                                                                                                :vertical="true" />
                                                                                        @else
                                                                                            <livewire:select-option
                                                                                                name="SUB_CLASS_ID"
                                                                                                titleName="Item Sub-class"
                                                                                                :options="[]"
                                                                                                :zero="true"
                                                                                                wire:model='SUB_CLASS_ID'
                                                                                                :vertical="true">
                                                                                        @endif
                                                                                    </div>
                                                                                @endif

                                                                                @if ($TYPE != 6)
                                                                                    <div class="col-md-12">
                                                                                        @if ($TYPE < 2)
                                                                                            <livewire:select-option
                                                                                                name="GL_ACCOUNT_ID"
                                                                                                :options="$accounts"
                                                                                                :zero="true"
                                                                                                titleName="Income Accounts"
                                                                                                wire:model='GL_ACCOUNT_ID'
                                                                                                :vertical="true" />
                                                                                        @else
                                                                                            <livewire:select-option
                                                                                                name="GL_ACCOUNT_ID"
                                                                                                :options="$accounts"
                                                                                                :zero="true"
                                                                                                titleName="GL Accounts"
                                                                                                wire:model='GL_ACCOUNT_ID'
                                                                                                :vertical="true" />
                                                                                        @endif

                                                                                    </div>

                                                                                    <div class="col-md-12">
                                                                                        <livewire:custom-check-box
                                                                                            name="TAXABLE"
                                                                                            titleName="Taxable"
                                                                                            wire:model='TAXABLE' />
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                    @endif
                                                                    @if ($TYPE === 4 || $TYPE === 7)
                                                                        <div class="col-md-6">
                                                                            <livewire:select-option name="RATE_TYPE"
                                                                                :options="$rateType" :zero="false"
                                                                                titleName="Rate Type"
                                                                                wire:model='RATE_TYPE'
                                                                                :vertical="true" />
                                                                        </div>
                                                                    @endif
                                                                    @if ($TYPE <= 4 || $TYPE === 7)
                                                                        <div class="col-md-6">
                                                                            <div class="row">
                                                                                @if ($TYPE < 2)
                                                                                    <div class="col-md-12">
                                                                                        <livewire:select-option
                                                                                            name="ASSET_ACCOUNT_ID"
                                                                                            :options="$accounts"
                                                                                            :zero="true"
                                                                                            titleName="Asset Accounts"
                                                                                            wire:model='ASSET_ACCOUNT_ID'
                                                                                            :vertical="true" />
                                                                                    </div>
                                                                                @endif
                                                                                <div class="col-md-12">
                                                                                    <livewire:text-input name="NOTES"
                                                                                        titleName="Notes"
                                                                                        wire:model='NOTES'
                                                                                        :vertical="true"
                                                                                        maxlength='100' />
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <livewire:custom-check-box
                                                                                        name="INACTIVE"
                                                                                        titleName="Inactive"
                                                                                        wire:model='INACTIVE' />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-6">
                                                                            <div class="col-md-12">
                                                                                <livewire:custom-check-box
                                                                                    name="INACTIVE"
                                                                                    titleName="Inactive"
                                                                                    wire:model='INACTIVE' />
                                                                            </div>
                                                                        </div>

                                                                    @endif
                                                                    @if ($TYPE < 2)
                                                                        <div class="col-md-6">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <livewire:select-option
                                                                                        name="BASE_UNIT_ID"
                                                                                        :options="$units"
                                                                                        :zero="true"
                                                                                        titleName="Base Unit"
                                                                                        wire:model='BASE_UNIT_ID'
                                                                                        :vertical="true" />
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <livewire:select-option
                                                                                        name="PURCHASES_UNIT_ID"
                                                                                        :options="$units"
                                                                                        :zero="true"
                                                                                        titleName="Purchases Unit"
                                                                                        wire:model='PURCHASES_UNIT_ID'
                                                                                        :vertical="true" />
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <livewire:select-option
                                                                                        name="SHIPPING_UNIT_ID"
                                                                                        :options="$units"
                                                                                        :zero="true"
                                                                                        titleName="Shipping Unit"
                                                                                        wire:model='SHIPPING_UNIT_ID'
                                                                                        :vertical="true" />
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <livewire:select-option
                                                                                        name="SALES_UNIT_ID"
                                                                                        :options="$units"
                                                                                        :zero="true"
                                                                                        titleName="Sales Unit"
                                                                                        wire:model='SALES_UNIT_ID'
                                                                                        :vertical="true" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <button type="submit" wire:loading.attr='hidden'
                                                                class="btn btn-sm btn-success">{{ $ID === 0 ? 'Save' : 'Update' }}
                                                            </button>
                                                            <div wire:loading.delay>
                                                                <span class="spinner"></span>
                                                            </div>
                                                        </div>
                                                        <div class="text-right col-md-6">
                                                            @if ($ID > 0)
                                                                <a id="new" title="Create"
                                                                    href="{{ route('maintenanceinventoryitem_create') }}"
                                                                    class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-plus"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            {{-- gen end --}}

                                        </div>
                                        @if ($TYPE == 1 || $TYPE == 6)
                                            <div class="tab-pane fade @if ($activeTab == 'other') show active @endif"
                                                id="custom-tabs-three-other" role="tabpanel"
                                                aria-labelledby="custom-tabs-three-other-tab">
                                                <div class="row">
                                                    @if ($TYPE === 1)
                                                        @livewire('ItemPage.ItemComponentPanel', ['itemId' => $ID, 'itemTypeName' => 'Component List'])
                                                    @elseif ($TYPE === 6)
                                                        @livewire('ItemPage.ItemComponentPanel', ['itemId' => $ID, 'itemTypeName' => 'Group List'])
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if ($TYPE < 2)
                                            <div class="tab-pane fade @if ($activeTab == 'inv') show active @endif"
                                                id="custom-tabs-three-inventory" role="tabpanel"
                                                aria-labelledby="custom-tabs-three-inventory-tab">
                                                <div class="row">
                                                    <div class="col-md-12 mt-2">
                                                        @livewire('ItemPage.ItemInventoryPanel', ['itemId' => $ID])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade @if ($activeTab == 'unit') show active @endif"
                                                id="custom-tabs-three-unit" role="tabpanel"
                                                aria-labelledby="custom-tabs-three-unit-tab">
                                                <div class="row">
                                                    <div class="col-md-12 mt-2 mb-2">
                                                        @livewire('ItemPage.ItemUnitPanel', ['itemId' => $ID])
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if ($TYPE < 5)
                                            <div class="tab-pane fade @if ($activeTab == 'plv') show active @endif"
                                                id="custom-tabs-three-pricelevel" role="tabpanel"
                                                aria-labelledby="custom-tabs-three-pricelevel-tab">
                                                @livewire('ItemPage.ItemPriceLevelPanel', ['itemId' => $ID])
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
