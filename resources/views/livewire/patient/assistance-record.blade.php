 <div class="row">
     <div class="col-md-4">
         @livewire('Patient.AssistanceRecordSum')
     </div>
     <div class="col-md-8">
         <div class="card card-primary card-outline card-outline-tabs">
             <div class="card-header p-0 border-bottom-0">
                 <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'dswd') active @endif"
                             id="custom-tabs-four-dswd-tab" wire:click="SelectTab('dswd')" data-toggle="pill"
                             href="#custom-tabs-four-dswd" role="tab" aria-controls="custom-tabs-four-dswd"
                             aria-selected="true">
                             DSWD
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'pcso') active @endif"
                             id="custom-tabs-four-pcso-tab" wire:click="SelectTab('pcso')" data-toggle="pill"
                             href="#custom-tabs-four-pcso" role="tab" aria-controls="custom-tabs-four-pcso"
                             aria-selected="true">
                             PCSO
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'lingap') active @endif"
                             id="custom-tabs-four-lingap-tab" wire:click="SelectTab('lingap')" data-toggle="pill"
                             href="#custom-tabs-four-lingap" role="tab" aria-controls="custom-tabs-four-lingap"
                             aria-selected="true">
                             LINGAP
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'balance') active @endif"
                             id="custom-tabs-four-balance-tab" wire:click="SelectTab('balance')" data-toggle="pill"
                             href="#custom-tabs-four-balance" role="tab" aria-controls="custom-tabs-four-balance"
                             aria-selected="true">
                             Balance
                         </a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link @if ($tab == 'cash') active @endif"
                             id="custom-tabs-four-cash-tab" wire:click="SelectTab('cash')" data-toggle="pill"
                             href="#custom-tabs-four-cash" role="tab" aria-controls="custom-tabs-four-cash"
                             aria-selected="true">
                             Cash
                         </a>
                     </li>
                 </ul>
             </div>
             <div class="card-body">
                 <div class="tab-content" id="custom-tabs-four-tabContent">
                     <div class="tab-pane fade @if ($tab == 'dswd') show active @endif"
                         id="custom-tabs-four-dswd" role="tabpanel" aria-labelledby="custom-tabs-four-dswd-tab">
                         @livewire('Patient.AssistanceRecordDswd')
                     </div>
                     <div class="tab-pane fade @if ($tab == 'pcso') show active @endif"
                         id="custom-tabs-four-pcso" role="tabpanel" aria-labelledby="custom-tabs-four-pcso-tab">
                         @livewire('Patient.AssistanceRecordPcso')
                     </div>
                     <div class="tab-pane fade @if ($tab == 'lingap') show active @endif"
                         id="custom-tabs-four-lingap" role="tabpanel" aria-labelledby="custom-tabs-four-lingap-tab">
                         @livewire('Patient.AssistanceRecordLingap')
                     </div>
                     <div class="tab-pane fade @if ($tab == 'balance') show active @endif"
                         id="custom-tabs-four-balance" role="tabpanel" aria-labelledby="custom-tabs-four-balance-tab">
                         @livewire('Patient.AssistanceRecordBalance')
                     </div>
                     <div class="tab-pane fade @if ($tab == 'cash') show active @endif"
                         id="custom-tabs-four-cash" role="tabpanel" aria-labelledby="custom-tabs-four-cash-tab">
                         @livewire('Patient.AssistanceRecordCash')
                     </div>


                 </div>
             </div>

         </div>
     </div>
 </div>
