     <div class="small-box text-bg-primary">
         <div class="inner">
             <p class="h6">Treatment Report Summary </p>
         </div>
         <div class="inner" style="height:300px;">
             <div class="row">

                 <div class="col-8">
                     <div class="text-xs">Month</div>
                     <select class="text-xs w-100" wire:model.live='month'>
                         @foreach ($monthlyList as $list)
                             <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                         @endforeach
                     </select>
                 </div>
                 <div class='col-4'>
                     <div class="text-xs">Year</div>
                     <select class="text-xs w-100" wire:model.live='year'>
                         @foreach ($yearList as $list)
                             <option value="{{ $list['ID'] }}">{{ $list['NAME'] }}</option>
                         @endforeach
                     </select>
                 </div>
             </div>

             <table class="table table-bordered table-hover">
                 <thead>
                     <tr>
                         <th>Branch</th>
                         <th class="text-center">No of Treament</th>
                         <th class="text-center">Philheatlh</th>
                         <th class="text-center">Priming</th>
                         <th class="text-center">Regular Rate</th>
                     </tr>

                 </thead>

                 <tbody>
                     @foreach ($locaitonList as $list)
                         <tr>
                             <td>{{ $list->NAME }}</td>
                             <td class="text-center">1000/120</td>
                             <td class="text-center">1000/120</td>
                             <td class="text-center">1000/120</td>
                             <td class="text-center">1000/120</td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>

         </div>

     </div>
