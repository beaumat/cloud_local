<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Sales</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-label-sm" for="DefaultPaymentTermsId">
                                Payment Terms :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model.live.debounce='DefaultPaymentTermsId' name="DefaultPaymentTermsId"
                                    id="DefaultPaymentTermsId" class="form-control form-control-sm">
                                    @foreach ($paymentTermList as $item)
                                        <option value="{{ $item->ID }}"> {{ $item->DESCRIPTION }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                    <button wire:click="saveOn('DefaultPaymentTermsId','{{ $DefaultPaymentTermsId }}')"
                                        type="button" class="btn btn-sm btn-success btn-flat">
                                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-sm" for="location_default">
                                Payment Method :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model.live.debounce='DefaultPaymentMethodId' name="DefaultPaymentMethodId"
                                    id="DefaultPaymentMethodId" class="form-control form-control-sm">
                                    @foreach ($paymentMethodList as $item)
                                        <option value="{{ $item->ID }}"> {{ $item->DESCRIPTION }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                    <button
                                        wire:click="saveOn('DefaultPaymentMethodId','{{ $DefaultPaymentMethodId }}')"
                                        type="button" class="btn btn-sm btn-success btn-flat">
                                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-sm" for="CreditLimitPolicy">
                                Credit LimitPolicy :
                            </label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model.live.debounce='CreditLimitPolicy' name="CreditLimitPolicy"
                                    id="CreditLimitPolicy" class="form-control form-control-sm">
                                    @foreach ($creditLimitPolicyList as $item)
                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}</option>
                                    @endforeach
                                </select>

                                <span class="input-group-append">
                                    <button wire:click="saveOn('CreditLimitPolicy','{{ $CreditLimitPolicy }}')"
                                        type="button" class="btn btn-sm btn-success btn-flat">
                                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3  col-form-label col-form-sm" for="ArAgingLimit">A/R Aging Limit
                                :</label>
                            <div class="col-md-9 input-group input-group-sm">
                                <select wire:model.live.debounce='ArAgingLimit' name="ArAgingLimit" id="ArAgingLimit"
                                    class="form-control form-control-sm">
                                    @foreach ($arAgingList as $item)
                                        <option value="{{ $item['ID'] }}"> {{ $item['NAME'] }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-append">
                                    <button wire:click="saveOn('ArAgingLimit','{{ $ArAgingLimit }}')" type="button"
                                        class="btn btn-sm btn-success btn-flat">
                                        <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Received Payments</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('AutoApplyPayments','{{ $AutoApplyPayments }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='AutoApplyPayments' type="checkbox">
                                <label>Auto Apply Payment</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('AutoCalcPayments','{{ $AutoCalcPayments }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='AutoCalcPayments' type="checkbox">
                                <label>Auto Calculate Payment</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('UseUndepositedFunds','{{ $UseUndepositedFunds }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='UseUndepositedFunds' type="checkbox">
                                <label>Use Undeposited Funds</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title"> Statement</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('ShowInvoiceDetailsOnStatement','{{ $ShowInvoiceDetailsOnStatement }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='ShowInvoiceDetailsOnStatement' type="checkbox">
                                <label>Show invoice details on statement</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('CreateStatementWithZeroBalance','{{ $CreateStatementWithZeroBalance }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='CreateStatementWithZeroBalance' type="checkbox">
                                <label>Create statement with zero balance</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('PrintDueDateOnStatement','{{ $PrintDueDateOnStatement }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='PrintDueDateOnStatement' type="checkbox">
                                <label>Print due date on statement</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('ShowPostdatedTransactions','{{ $ShowPostdatedTransactions }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='ShowPostdatedTransactions' type="checkbox">
                                <label>Show postdated transactions</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-sm">
            <div class="pt-1 pb-1 card-header bg-primary">
                <h3 class="card-title">Others</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('AllowPriceOverride','{{ $AllowPriceOverride }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='AllowPriceOverride' type="checkbox">
                                <label>Allow price override</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('AllowBlankInSellingPrice','{{ $AllowBlankInSellingPrice }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='AllowBlankInSellingPrice' type="checkbox">
                                <label>Allow zero in selling price</label>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('AllowPriceLevel','{{ $AllowPriceLevel }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='AllowPriceLevel' type="checkbox">
                                <label>Allow price level</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('WarnWhenPriceBelowCost','{{ $WarnWhenPriceBelowCost }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='WarnWhenPriceBelowCost' type="checkbox">
                                <label>Warning when selling price is below cost</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button
                                    wire:click="saveOn('EnableBatchNumberInSalesOrder','{{ $EnableBatchNumberInSalesOrder }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='EnableBatchNumberInSalesOrder' type="checkbox">
                                <label>Enable Bath Number in sales order</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <button wire:click="saveOn('HideInactiveCustomer','{{ $HideInactiveCustomer }}')"
                                    class="btn btn-sm btn-success mr-4">
                                    <i class="fa fa-floppy-o text-sm" aria-hidden="true"></i>
                                </button>
                                <input wire:model.live='HideInactiveCustomer' type="checkbox">
                                <label>Hide inactive customer</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
