<div class="row">
    <div class="col-md-4">
        <label class="text-xs text-primary">NEPHROLOGIST:</label>
        <div class="row m-1">
            <label class="text-sm col-md-12">SPECIAL ENDORSEMENT</label>
            <input type="text" class="form-control form-control-sm" wire:model='NEPHROLOGIST' name="NEPHROLOGIST" />
            <textarea class="form-control from-contro-sm" rows="12" wire:model='SPECIAL_ENDORSEMENT' name="SPECIAL_ENDORSEMENT"></textarea>
        </div>
    </div>
    <div class="col-md-4">
        <label class="text-xs text-primary">DIAGNOSIS:</label>
        <div class="row m-1">
            <label class="text-sm col-md-12">STANDING ORDER</label>
            <input type="text" class="form-control form-control-sm" wire:model='DIAGNOSIS' name="DIAGNOSIS" />
            <textarea class="form-control from-contro-sm" rows="12" wire:model='STANDING_ORDER' name="STANDING_ORDER"></textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <label class="col-md-12 text-center text-primary text-xs">FISTULA / GRAFT ACCESS</label>
            <table class="table table-sm ">
                <tbody class="text-xs">
                    <tr>
                        <td>ACCESS TYPE</td>
                        <td>
                            <livewire:checkbox-input name="ACCESS_TYPE_FISTUAL" titleName="FISTULA"
                                wire:model='ACCESS_TYPE_FISTUAL' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="ACCESS_TYPE_GRAFT" titleName="GRAFT"
                                wire:model='ACCESS_TYPE_GRAFT' />
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-6 col-6 text-right">
                                    <livewire:checkbox-input name="ACCESS_TYPE_R" titleName="R"
                                        wire:model='ACCESS_TYPE_R' />
                               
                                </div>
                                <div class="col-md-6 col-6 pl-4">
                                    <livewire:checkbox-input name="ACCESS_TYPE_L" titleName="L"
                                    wire:model='ACCESS_TYPE_L' />
                                </div>
                            </div>




                        </td>
                    </tr>
                    <tr>
                        <td>BRUIT</td>
                        <td>

                            <livewire:checkbox-input name="BRUIT_STRONG" titleName="STRONG" wire:model='BRUIT_STRONG' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="BRUIT_WEAK" titleName="WEAK" wire:model='BRUIT_WEAK' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="BRUIT_ABSENT" titleName="ABSENT" wire:model='BRUIT_ABSENT' />
                        </td>
                    </tr>
                    <tr>
                        <td>THRILL</td>
                        <td>
                            <livewire:checkbox-input name="THRILL_STRONG" titleName="STRONG"
                                wire:model='THRILL_STRONG' />
                        </td>
                        <td>

                            <livewire:checkbox-input name="THRILL_WEAK" titleName="WEAK" wire:model='THRILL_WEAK' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="THRILL_ABSENT" titleName="ABSENT"
                                wire:model='THRILL_ABSENT' />
                        </td>
                    </tr>
                    <tr>
                        <td>HEMOTAMA</td>
                        <td>
                            <livewire:checkbox-input name="HEMOTOMA_PRESENT" titleName="PRESENT"
                                wire:model='HEMOTOMA_PRESENT' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="HEMOTOMA_ABSENT" titleName="ABSENT"
                                wire:model='HEMOTOMA_ABSENT' />
                        </td>
                        <td>
                            <label> Others:</label>
                            <input type="text" class="" wire:model='ACCESS_OTHERS'
                                name="ACCESS_OTHERS" />

                        </td>
                    </tr>
               
                </tbody>

            </table>


            <label class="col-md-12 text-center text-primary text-xs">CVC ACCESS</label>
            
            <table class="table table-sm">
                <tbody class="text-xs ">
                    <tr>
                        <td>
                            <livewire:checkbox-input name="CVC_SUBCATH" titleName="SUBCATH" wire:model='CVC_SUBCATH' />
                        </td>
                        <td>

                            <livewire:checkbox-input name="CVC_JUGCATH" titleName="JUGCATH" wire:model='CVC_JUGCATH' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="CVC_FEMCATH" titleName="FEMCATH" wire:model='CVC_FEMCATH' />
                        </td>
                        <td>
                            <livewire:checkbox-input name="CVC_PERMCATH" titleName="PERMCATH"
                                wire:model='CVC_PERMCATH' />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Location:
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <livewire:checkbox-input name="CVC_LOCATION_R" titleName="R"
                                        wire:model='CVC_LOCATION_R' />
                                </div>
                                <div class="col-md-6 col-6">
                                    <livewire:checkbox-input name="CVC_LOCATION_L" titleName="L"
                                        wire:model='CVC_LOCATION_L' />
                                </div>
                            </div>

                        </td>
                    </tr>
                </tbody>

            </table>

            <table class="table table-sm table-bordered">
                <thead class="text-xs">
                    <tr>
                        <th>CATHETER PORTS</th>
                        <th>ARTERIAL</th>
                        <th>VENOUS</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <tr>
                        <td>GOOD FLOW</td>
                        <td> <input type="checkbox" wire:model='CVC_A_GOOD_FLOW' name="CVC_A_GOOD_FLOW" /></td>
                        <td> <input type="checkbox" wire:model='CVC_v_GOOD_FLOW' name="CVC_v_GOOD_FLOW" /></td>
                    </tr>
                    <tr>
                        <td>W/RESISTANCE</td>
                        <td> <input type="checkbox" wire:model='CVC_A_WRESISTANT' name="CVC_A_WRESISTANT" /></td>
                        <td> <input type="checkbox" wire:model='CVC_v_WRESISTANT' name="CVC_v_WRESISTANT" /></td>
                    </tr>
                    <tr>
                        <td>CLOTTED/NON PATENT</td>
                        <td> <input type="checkbox" wire:model='CVC_A_CLOTTED_NON_PATENT'
                                name="CVC_A_CLOTTED_NON_PATENT" /></td>
                        <td> <input type="checkbox" wire:model='CVC_v_CLOTTED_NON_PATENT'
                                name="CVC_v_CLOTTED_NON_PATENT" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
