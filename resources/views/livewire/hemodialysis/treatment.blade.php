<div class="row">
    <div class="col-md-4">
        <table class="table table-sm table-bordered">
            <thead class="text-xs">
                <tr>
                    <th class="col-4"></th>
                    <th class="text-center col-4">Last Treatment</th>
                    <th class="text-center col-4">Today Treatment</th>
                </tr>

            </thead>
            <thead class="text-xs">
                <tr>
                    <th></th>
                    <th class="text-center">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                PRE
                            </div>
                            <div class="col-md-6 text-center">
                                POST
                            </div>
                        </div>
                    </th>
                    <th class="text-center">
                        <div class="row">
                            <div class="col-md-6">
                                PRE
                            </div>
                            <div class="col-md-6">
                                POST
                            </div>
                        </div>
                    </th>
                </tr>

            </thead>
            <tbody class="text-xs">
                <tr>
                    <td>WEIGHT</td>
                    <td>
                        <div class="row" id="LAST">
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row" id="TODAY">
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='PRE_WEIGHT' class="text-xs w-100 text-right" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='POST_WEIGHT' class="text-xs w-100 text-right" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>BLOOD PRESSURE</td>
                    <td>
                        <div class="row" id="LAST">
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row" id="TODAY">
                            <div class="col-md-6">
                                <input wire:model.live.debounce.1000ms='PRE_BLOOD_PRESSURE' type="number" class="text-xs w-100 text-right" />
                            </div>
                            <div class="col-md-6">
                                <input wire:model.live.debounce.1000ms='POST_BLOOD_PRESSURE' type="number" class="text-xs w-100 text-right" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>HEART RATE</td>
                    <td>
                        <div class="row" id="LAST">
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row" id="TODAY">
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='PRE_HEART_RATE' class="text-xs w-100 text-right" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='POST_HEART_RATE' class="text-xs w-100 text-right" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>O2 SATURATION</td>
                    <td>
                        <div class="row" id="LAST">
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row" id="TODAY">
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='PRE_O2_SATURATION' class="text-xs w-100 text-right" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='POST_O2_SATURATION' class="text-xs w-100 text-right" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>TEMPERATURE</td>
                    <td>
                        <div class="row" id="LAST">
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                            <div class="col-md-6 text-center">
                                0.00
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row" id="TODAY">
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='PRE_TEMPERATURE' class="text-xs w-100 text-right" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" wire:model.live.debounce.1000ms='POST_TEMPERATURE' class="text-xs w-100 text-right" />
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="row">
            <label class="col-md-2">UF GOAL</label>
            <div class="col-md-10">
                <input type="text" class="form-control form-control-sm text-xs" />
            </div>

        </div>
        <table class="table table-sm table-bordered">
            <tbody class="text-xs">

                <tr>
                    <td class="col-4">
                        <div class="row">
                            <label class="col-md-6">BFR</label>
                            <input type="text" />
                        </div>

                        <div class="row">
                            <label class="col-md-6">DFR</label>
                            <input type="text" />
                        </div>

                        <div class="row">
                            <label class="col-md-6">DURATION</label>
                            <input type="text" />
                        </div>
                        <div class="row">
                            <label class="col-md-6">DIALYZER</label>
                            <input type="text" />
                        </div>
                        <div class="row">
                            <label class="col-md-6">RE-USE NO.</label>
                            <input type="text" />
                        </div>
                        <div class="row">
                            <label class="col-md-6">HERAPIN</label>
                            <input type="text" />
                        </div>
                        <div class="row">
                            <label class="col-md-6">FLUSHING</label>
                            <input type="text" />
                        </div>
                    </td>
                    <td class="col-4">
                        <div class="row">
                            <label class="col-md-12 text-center">SAFETY CHECK</label>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" />
                                        <label class="px-1">MACHINE TEST</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" />
                                        <label class="px-1">SECURED CONNECTION</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" />
                                        <label class="px-1">SALINE LINE DOUBLE CAMP</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-4">CONNECTIVITY</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-4">DIAIYSATE TEMP</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control form-control-sm" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        RESIDUAL TEST :
                                        <input type="checkbox" />
                                        <label class="px-1">NIGATIVE</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <td class="col-4">
                        <div class="row">
                            <label class="col-md-12 text-center">DIAIYSATE BATH</label>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" />
                                    <label class="px-1">STANDARD HC03</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" />
                                    <label class="px-1">ACID</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-4 text-right">Na</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control form-control-sm" />
                            </div>
                            <label class="col-md-4">meg/L</label>
                        </div>
                        <div class="row">
                            <label class="col-md-4 text-right">K+</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control form-control-sm" />
                            </div>
                            <label class="col-md-4">meg/L</label>
                        </div>
                        <div class="row">
                            <label class="col-md-4 text-right">Ca+</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control form-control-sm" />
                            </div>
                            <label class="col-md-4">meg/L</label>
                        </div>
                    </td>

    </div>
    </td>
    <td></td>
    </tr>
    </tbody>
    </table>
</div>
</div>
