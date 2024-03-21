<div class="row">
    <div class="col-md-6">
        <div class="row">
            <label class="text-center col-md-12 text-xs text-primary">PRE-HEMODIALYSIS ASSESSMENT</label>
        </div>
        <table class="table table-sm border border-secondary">
            <tbody class="text-xs">
                <tr>
                    <td><Label class="text-info">MOBILIZATION</Label></td>
                    <td><Label class="text-info">LUNGS</Label></td>
                    <td><Label class="text-info">FLUID STATUS</Label></td>
                    <td><Label class="text-info">HEART</Label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_AMBULATORY' name="PRE_ASSIST_AMBULATORY" />
                        <Label class="text-dark">AMBULATORY</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_CLEAR' name="PRE_ASSIST_CLEAR" />
                        <Label class="text-dark">CLEAR</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_DISTENDID_JUGULAR_VIEW'
                            name="PRE_ASSIST_DISTENDID_JUGULAR_VIEW" />
                        <Label class="text-dark"> DISTENDED JUGULAR VEIN</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSSIT_REGULAR' name="PRE_ASSSIT_REGULAR" />
                        <Label class="text-dark">REGULAR</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_AMBULATORY_W_ASSIST'
                            name="PRE_ASSIST_AMBULATORY_W_ASSIST" />
                        <Label class="text-dark">AMBULATORY W/ ASSEST</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_CRACKLES' name="PRE_ASSIST_CRACKLES" />
                        <Label class="text-dark">CRACKLES</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_ASCITES' name="PRE_ASSIST_ASCITES" />
                        <Label class="text-dark">ASCITES</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_IRREGULAR' name="PRE_ASSIST_IRREGULAR" />
                        <Label class="text-dark">IRREGULAR</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_WHEEL_CHAIR' name="PRE_ASSIST_WHEEL_CHAIR" />
                        <Label class="text-dark">WHEEL CHAIR</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_RHONCHI' name="PRE_ASSIST_RHONCHI" />
                        <Label class="text-dark">RHONCHI</Label>
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>

                        <Label class="text-info">L.O.C</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_WHEEZES' name="PRE_ASSIST_WHEEZES" />
                        <Label class="text-dark">WHEEZES</Label>
                    </td>
                    <td>

                        <input type="checkbox" wire:model='PRE_ASSIST_EDEMA' name="PRE_ASSIST_EDEMA" />
                        <Label class="text-dark">EDEMA</Label>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_CONSCIOUS' name="PRE_ASSIST_CONSCIOUS" />
                        <Label class="text-dark">CONSCIOUS</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_RALES' name="PRE_ASSIST_RALES" />
                        <Label class="text-dark">RALES</Label>
                    </td>
                    <td>
                        LOCATION: <input type="text" wire:model='PRE_ASSIST_EDEMA_LOCAITON'
                            name="PRE_ASSIST_EDEMA_LOCAITON" />

                    </td>

                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_COHERENT' name="PRE_ASSIST_COHERENT" />
                        <Label class="text-dark">COHERENT</Label>
                    </td>
                    <td>

                    </td>
                    <td>
                        DEPTH: <input type="text" wire:model='PRE_ASSIST_EDEMA_DEPTH'
                            name="PRE_ASSIST_EDEMA_DEPTH" />

                    </td>

                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_DISORIENTED' name="PRE_ASSIST_DISORIENTED" />
                        <Label class="text-dark">DISORIENTED</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='PRE_ASSIST_DROWSY' name="PRE_ASSIST_DROWSY" />
                        <Label class="text-dark">DROWSY</Label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-6">
        <div class="row">
            <label class="text-center col-md-12 text-xs text-primary">POST-HEMODIALYSIS ASSESSMENT</label>
        </div>
        <table class="table table-sm border border-secondary">
            <tbody class="text-xs">
                <tr>
                    <td><Label class="text-info">MOBILIZATION</Label></td>
                    <td><Label class="text-info">LUNGS</Label></td>
                    <td><Label class="text-info">FLUID STATUS</Label></td>
                    <td><Label class="text-info">HEART</Label></td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_AMBULATORY' name="POST_ASSIST_AMBULATORY" />
                        <Label class="text-dark">AMBULATORY</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_CLEAR' name="POST_ASSIST_CLEAR" />
                        <Label class="text-dark">CLEAR</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_DISTENDID_JUGULAR_VIEW'
                            name="POST_ASSIST_DISTENDID_JUGULAR_VIEW" />
                        <Label class="text-dark"> DISTENDED JUGULAR VEIN</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSSIT_REGULAR' name="POST_ASSSIT_REGULAR" />
                        <Label class="text-dark">REGULAR</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_AMBULATORY_W_ASSIST'
                            name="POST_ASSIST_AMBULATORY_W_ASSIST" />
                        <Label class="text-dark">AMBULATORY W/ ASSEST</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_CRACKLES' name="POST_ASSIST_CRACKLES" />
                        <Label class="text-dark">CRACKLES</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_ASCITES' name="POST_ASSIST_ASCITES" />
                        <Label class="text-dark">ASCITES</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_IRREGULAR' name="POST_ASSIST_IRREGULAR" />
                        <Label class="text-dark">IRREGULAR</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_WHEEL_CHAIR' name="POST_ASSIST_WHEEL_CHAIR" />
                        <Label class="text-dark">WHEEL CHAIR</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_RHONCHI' name="POST_ASSIST_RHONCHI" />
                        <Label class="text-dark">RHONCHI</Label>
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>

                        <Label class="text-info">L.O.C</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_WHEEZES' name="POST_ASSIST_WHEEZES" />
                        <Label class="text-dark">WHEEZES</Label>
                    </td>
                    <td>

                        <input type="checkbox" wire:model='POST_ASSIST_EDEMA' name="POST_ASSIST_EDEMA" />
                        <Label class="text-dark">EDEMA</Label>
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_CONSCIOUS' name="POST_ASSIST_CONSCIOUS" />
                        <Label class="text-dark">CONSCIOUS</Label>
                    </td>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_RALES' name="POST_ASSIST_RALES" />
                        <Label class="text-dark">RALES</Label>
                    </td>
                    <td>
                        LOCATION: <input type="text" wire:model='POST_ASSIST_EDEMA_LOCAITON'
                            name="POST_ASSIST_EDEMA_LOCAITON" />

                    </td>

                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_COHERENT' name="POST_ASSIST_COHERENT" />
                        <Label class="text-dark">COHERENT</Label>
                    </td>
                    <td>

                    </td>
                    <td>
                        DEPTH: <input type="text" wire:model='POST_ASSIST_EDEMA_DEPTH'
                            name="POST_ASSIST_EDEMA_DEPTH" />

                    </td>

                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_DISORIENTED' name="POST_ASSIST_DISORIENTED" />
                        <Label class="text-dark">DISORIENTED</Label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" wire:model='POST_ASSIST_DROWSY' name="POST_ASSIST_DROWSY" />
                        <Label class="text-dark">DROWSY</Label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
