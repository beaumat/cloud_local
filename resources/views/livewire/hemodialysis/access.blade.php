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
                            <input type="checkbox" wire:model='ACCESS_TYPE_FISTUAL' name="ACCESS_TYPE_FISTUAL" />
                            <label>FISTULA</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='ACCESS_TYPE_GRAFT' name="ACCESS_TYPE_GRAFT" />
                            <label>GRAFT</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='ACCESS_TYPE_R' name="ACCESS_TYPE_R" />
                            <label>R</label>
                            <input type="checkbox" wire:model='ACCESS_TYPE_L' name="ACCESS_TYPE_L" />
                            <label>L</label>
                        </td>
                    </tr>
                    <tr>
                        <td>BRUIT</td>
                        <td>
                            <input type="checkbox" wire:model='BRUIT_STRONG' name="BRUIT_STRONG" />
                            <label>STRONG</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='BRUIT_WEAK' name="BRUIT_WEAK" />
                            <label>WEAK</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='BRUIT_ABSENT' name="BRUIT_ABSENT" />
                            <label>ABSENT</label>
                        </td>
                    </tr>
                    <tr>
                        <td>THRILL</td>
                        <td>
                            <input type="checkbox" wire:model='THRILL_STRONG' name="THRILL_STRONG" />
                            <label>STRONG</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='THRILL_WEAK' name="THRILL_WEAK" />
                            <label>WEAK</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='THRILL_ABSENT' name="THRILL_ABSENT" />
                            <label>ABSENT</label>

                        </td>
                    </tr>
                    <tr>
                        <td>HEMOTAMA</td>
                        <td>
                            <input type="checkbox" wire:model='HEMOTOMA_PRESENT' name="HEMOTOMA_PRESENT" />
                            <label>PRESENT</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='HEMOTOMA_ABSENT' name="HEMOTOMA_ABSENT" />
                            <label>ABSENT</label>
                        </td>
                        <td>


                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label> Others:</label>
                            <input type="text" class="form-control form-control-sm" wire:model='ACCESS_OTHERS'
                                name="ACCESS_OTHERS" />
                        </td>
                    </tr>
                </tbody>

            </table>


            <label class="col-md-12 text-center text-primary text-xs">CVC ACCESS</label>
            <table class="table table-sm ">
                <tbody class="text-xs">
                    <tr>
                        <td>
                            <input type="checkbox" wire:model='CVC_SUBCATH' name="CVC_SUBCATH" />
                            <label>SUBCATH</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='CVC_JUGCATH' name="CVC_JUGCATH" />
                            <label>JUGCATH</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='CVC_FEMCATH' name="CVC_FEMCATH" />
                            <label>FEMCATH</label>
                        </td>
                        <td>
                            <input type="checkbox" wire:model='CVC_PERMCATH' name="CVC_PERMCATH" />
                            <label>PERMCATH</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Location:
                        </td>
                        <td>
                            <input type="checkbox" wire:model='CVC_LOCATION_R' name="CVC_LOCATION_R" />
                            <label>R</label>
                            <input type="checkbox" wire:model='CVC_LOCATION_L' name="CVC_LOCATION_L" />
                            <label>L</label>
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
