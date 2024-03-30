<div>

        <div class="row">
            <div class="col-md-4">
                <table class="table table-sm table-bordered">
                    <thead class="text-xs">
                        <tr>
                            <th class="col-4"></th>
                            <th class="text-center col-4 text-info">Last Treatment</th>
                            <th class="text-center col-4 text-info">Today Treatment</th>
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
                            <td class="text-primary">WEIGHT</td>
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
                                        <input type="number" wire:model='PRE_WEIGHT'
                                            class="text-xs w-100 text-right" />

                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" wire:model='POST_WEIGHT'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary">BLOOD PRESSURE</td>
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
                                        <input wire:model='PRE_BLOOD_PRESSURE' type="number"
                                            class="text-xs w-100 text-right" />
                                    </div>
                                    <div class="col-md-6">
                                        <input wire:model='POST_BLOOD_PRESSURE' type="number"
                                            class="text-xs w-100 text-right" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary">HEART RATE</td>
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
                                        <input type="number" wire:model='PRE_HEART_RATE'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" wire:model='POST_HEART_RATE'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary">O2 SATURATION</td>
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
                                        <input type="number" wire:model='PRE_O2_SATURATION'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" wire:model='POST_O2_SATURATION'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-primary">TEMPERATURE</td>
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
                                        <input type="number" wire:model='PRE_TEMPERATURE'
                                            class="text-xs w-100 text-right" />
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" wire:model='POST_TEMPERATURE'
                                            class="text-xs w-100 text-right" />
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
                        <input type="text" wire:model='UF_GOAL' name="UF_GOAL"
                            class="form-control form-control-sm text-xs" />
                    </div>

                </div>
                <table class="table table-sm table-bordered">
                    <tbody class="text-xs">
                        <tr>
                            <td class="col-4">
                                <div class="row">
                                    <label class="col-md-6">BFR</label>
                                    <input type="number" wire:model='BFR' name="BFR" />
                                </div>

                                <div class="row">
                                    <label class="col-md-6">DFR</label>
                                    <input type="number" wire:model='DFR' name="DFR" />
                                </div>

                                <div class="row">
                                    <label class="col-md-6">DURATION</label>
                                    <input type="number" wire:model='DURATION' name="DURATION" />
                                </div>
                                <div class="row">
                                    <label class="col-md-6">DIALYZER</label>
                                    <input type="text" wire:model='DIALYZER' name="DIALYZER" />
                                </div>
                                <div class="row">
                                    <label class="col-md-6">RE-USE NO.</label>
                                    <input type="number" wire:model='RE_USE_NO' name="RE_USE_NO" />
                                </div>
                                <div class="row">
                                    <label class="col-md-6">HERAPIN</label>
                                    <input type="text" wire:model='HERAPIN' name="HERAPIN" />
                                </div>
                                <div class="row">
                                    <label class="col-md-6">FLUSHING</label>
                                    <input type="text" wire:model='FLUSHING' name="FLUSHING" />
                                </div>
                            </td>
                            <td class="col-4">
                                <div class="row">
                                    <label class="col-md-12 text-center text-info">SAFETY CHECK</label>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="container">
                                            <livewire:checkbox-input name="SC_MACHINE_TEST" titleName="MACHINE TEST" wire:model='SC_MACHINE_TEST' />
                                            <livewire:checkbox-input name="SC_SECURED_CONNECTION" titleName="SECURED CONNECTION" wire:model='SC_SECURED_CONNECTION' />
                                            <livewire:checkbox-input name="SC_SALINE_LINE_DOUBLE_CLAMP" titleName="SALINE LINE DOUBLE CAMP" wire:model='SC_SALINE_LINE_DOUBLE_CLAMP' />
                                    
                                        </div>
                                      
                                        <div class="form-group">
                                            
                                            <div class="row">
                                                <label class="col-md-4">CONNECTIVITY</label>
                                                <div class="col-md-8">
                                                    <input type="number" wire:model='SC_CONDUCTIVITY'
                                                        name="SC_CONDUCTIVITY" class="form-control form-control-sm" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-4">DIAIYSATE TEMP</label>
                                                <div class="col-md-8">
                                                    <input type="number" wire:model='SC_DIALYATE_TEMP'
                                                        name="SC_DIALYATE_TEMP"
                                                        class="form-control form-control-sm" />
                                                </div>
                                            </div>
                                        </div>
                                        RESIDUAL TEST :
                                        <div class="container">                           
                                            <livewire:checkbox-input name="SC_RESIDUAL_TEST_NIGATIVE" titleName="NIGATIVE" wire:model='SC_RESIDUAL_TEST_NIGATIVE' />
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-4">
                                <div class="row">
                                    <label class="col-md-12 text-center text-info">DIAIYSATE BATH</label>
                                </div>
                                <div class="row">
                                    <div class="container">
                                        <livewire:checkbox-input name="DB_STANDARD_HCOA" titleName="STANDARD HC03" wire:model='DB_STANDARD_HCOA' />
                                        <livewire:checkbox-input name="DB_ACID" titleName="ACID" wire:model='DB_ACID' />             
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 text-right">Na</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model='DB_NA_MEG_L' name="DB_NA_MEG_L" />
                                    </div>
                                    <label class="col-md-4">meg/L</label>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 text-right">K+</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model='DB_KPLUS_MEG_L' name="DB_KPLUS_MEG_L" />
                                    </div>
                                    <label class="col-md-4">meg/L</label>
                                </div>
                                <div class="row">
                                    <label class="col-md-4 text-right">Ca+</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model='DB_CAPPLS_MEG_L' name="DB_CAPPLS_MEG_L" />
                                    </div>
                                    <label class="col-md-4">meg/L</label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>        
        </div>
</div>
