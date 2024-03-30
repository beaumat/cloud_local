<div class="row">
    <div class="col-md-6">
        <div class="row">
            <label class="text-center col-md-12 text-xs text-primary">PRE-HEMODIALYSIS ASSESSMENT</label>
        </div>
        <table class="table table-sm  border border-secondary">
            <tbody class="text-xs">
                <tr>
                    <td><Label class="text-info">MOBILIZATION</Label></td>
                    <td><Label class="text-info">LUNGS</Label></td>
                    <td><Label class="text-info">FLUID STATUS</Label></td>
                    <td><Label class="text-info">HEART</Label></td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_AMBULATORY" titleName="AMBULATORY"
                            wire:model='PRE_ASSIST_AMBULATORY' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_CLEAR" titleName="CLEAR"
                            wire:model='PRE_ASSIST_CLEAR' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_DISTENDID_JUGULAR_VIEW"
                            titleName="DISTENDED JUGULAR VEIN" wire:model='PRE_ASSIST_DISTENDID_JUGULAR_VIEW' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSSIT_REGULAR" titleName="REGULAR"
                            wire:model='PRE_ASSSIT_REGULAR' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_AMBULATORY_W_ASSIST" titleName="AMBULATORY W/ ASSEST"
                            wire:model='PRE_ASSIST_AMBULATORY_W_ASSIST' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_CRACKLES" titleName="CRACKLES"
                            wire:model='PRE_ASSIST_CRACKLES' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_ASCITES" titleName="ASCITES"
                            wire:model='PRE_ASSIST_ASCITES' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_IRREGULAR" titleName="IRREGULAR"
                            wire:model='PRE_ASSIST_IRREGULAR' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_WHEEL_CHAIR" titleName="WHEEL CHAIR"
                            wire:model='PRE_ASSIST_WHEEL_CHAIR' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_RHONCHI" titleName="RHONCHI"
                            wire:model='PRE_ASSIST_RHONCHI' />
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
                        <livewire:checkbox-input name="PRE_ASSIST_WHEEZES" titleName="WHEEZES"
                            wire:model='PRE_ASSIST_WHEEZES' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_EDEMA" titleName="EDEMA"
                            wire:model='PRE_ASSIST_EDEMA' />

                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>

                        <livewire:checkbox-input name="PRE_ASSIST_CONSCIOUS" titleName="CONSCIOUS"
                            wire:model='PRE_ASSIST_CONSCIOUS' />

                    </td>
                    <td>
                        <livewire:checkbox-input name="PRE_ASSIST_RALES" titleName="RALES"
                            wire:model='PRE_ASSIST_RALES' />
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

                        <livewire:checkbox-input name="PRE_ASSIST_COHERENT" titleName="COHERENT"
                            wire:model='PRE_ASSIST_COHERENT' />
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

                        <livewire:checkbox-input name="PRE_ASSIST_DISORIENTED" titleName="DISORIENTED"
                            wire:model='PRE_ASSIST_DISORIENTED' />
                    </td>
                </tr>
                <tr>
                    <td>

                        <livewire:checkbox-input name="PRE_ASSIST_DROWSY" titleName="DROWSY"
                            wire:model='PRE_ASSIST_DROWSY' />
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
                        <livewire:checkbox-input name="POST_ASSIST_AMBULATORY" titleName="AMBULATORY"
                            wire:model='POST_ASSIST_AMBULATORY' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_CLEAR" titleName="CLEAR"
                            wire:model='POST_ASSIST_CLEAR' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_DISTENDID_JUGULAR_VIEW"
                            titleName="DISTENDED JUGULAR VEIN" wire:model='POST_ASSIST_DISTENDID_JUGULAR_VIEW' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSSIT_REGULAR" titleName="REGULAR"
                            wire:model='POST_ASSSIT_REGULAR' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_AMBULATORY_W_ASSIST" titleName="AMBULATORY W/ ASSEST"
                            wire:model='POST_ASSIST_AMBULATORY_W_ASSIST' />

                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_CRACKLES" titleName="CRACKLES"
                            wire:model='POST_ASSIST_CRACKLES' />
                    </td>
                    <td>

                        <livewire:checkbox-input name="POST_ASSIST_ASCITES" titleName="ASCITES"
                            wire:model='POST_ASSIST_ASCITES' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_IRREGULAR" titleName="IRREGULAR"
                            wire:model='POST_ASSIST_IRREGULAR' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_WHEEL_CHAIR" titleName="WHEEL CHAIR"
                            wire:model='POST_ASSIST_WHEEL_CHAIR' />
                    </td>
                    <td>

                        <livewire:checkbox-input name="POST_ASSIST_RHONCHI" titleName="RHONCHI"
                            wire:model='POST_ASSIST_RHONCHI' />
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
                        <livewire:checkbox-input name="POST_ASSIST_WHEEZES" titleName="WHEEZES"
                            wire:model='POST_ASSIST_WHEEZES' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_EDEMA" titleName="EDEMA"
                            wire:model='POST_ASSIST_EDEMA' />
                    </td>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_CONSCIOUS" titleName="CONSCIOUS"
                            wire:model='POST_ASSIST_CONSCIOUS' />
                    </td>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_RALES" titleName="RALES"
                            wire:model='POST_ASSIST_RALES' />
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
                        <livewire:checkbox-input name="POST_ASSIST_COHERENT" titleName="COHERENT"
                            wire:model='POST_ASSIST_COHERENT' />
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
                        <livewire:checkbox-input name="POST_ASSIST_DISORIENTED" titleName="DISORIENTED"
                            wire:model='POST_ASSIST_DISORIENTED' />
                    </td>
                </tr>
                <tr>
                    <td>
                        <livewire:checkbox-input name="POST_ASSIST_DROWSY" titleName="DROWSY"
                            wire:model='POST_ASSIST_DROWSY' />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
