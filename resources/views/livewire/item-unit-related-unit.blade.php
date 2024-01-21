<div class="card bg-light">
    <h5 class="px-2 card-title">Related Units </h5>
    <div class="card-body ">
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
                <div class="col-md-3">
                    @if ($saveSuccess)
                        <livewire:select-option name="UNIT_ID1" titleName="Units" :options="$units" :zero="true"
                            wire:model='UNIT_ID' :vertical="false" />
                    @else
                        <livewire:select-option name="UNIT_ID2" titleName="Units" :options="$units" :zero="true"
                            wire:model='UNIT_ID' :vertical="false" />
                    @endif
                </div>
                <div class="col-md-2">
                    <livewire:number-input name="QUANTITY" titleName="Quantity" wire:model='QUANTITY'
                        :vertical="false" />
                </div>
                <div class="col-md-2">
                    <livewire:number-input name="RATE" titleName="Rate" wire:model='RATE' :vertical="false" />
                </div>
                <div class="col-md-3">
                    <livewire:text-input name="BARCODE" titleName="Barcode" wire:model='BARCODE' :vertical="false" />
                </div>
                <div class="text-right col-md-2">
                    <button type="submit" wire:loading.attr='hidden' class="text-white btn bg-light-blue btn-sm w-100"
                        style="margin-top: 40px;">
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
                    <th>Units</th>
                    <th class="col-1">Symbol</th>
                    <th class="text-right col-1">Qty</th>
                    <th class="text-right col-2">Rate</th>
                    <th>Barcode</th>
                    <th class="text-center col-2">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach ($unitRelatedList as $list)
                    <tr>
                        <td> {{ $list->NAME }}</td>
                        <td> {{ $list->SYMBOL }}</td>
                        <td class="text-right">
                            @if ($editItemId === $list->ID)
                                <input type="number" wire:model="newQUANTITY"
                                    class="form-control form-control-sm text-right">
                            @else
                                {{ number_format($list->QUANTITY, 1) }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($editItemId === $list->ID)
                                <input type="number" wire:model="newRATE"
                                    class="form-control form-control-sm text-right">
                            @else
                                {{ number_format($list->RATE, 2) }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <input type="text" wire:model="newBARCODE" class="form-control form-control-sm">
                            @else
                                {{ $list->BARCODE }}
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
                                    wire:click="editItem({{ $list->ID }},{{ $list->QUANTITY }},{{ $list->RATE }},'{{ $list->BARCODE }}')"
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
