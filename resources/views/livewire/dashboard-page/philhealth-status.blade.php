   <div class="small-box bg-white">
       <div class="inner">
           <p class="h6">Philhealth Monitoring </p>
       </div>
       <div class="inner" style="height:300px;">
           <div class="row">
               <div class="col-8">
                   &nbsp;
               </div>
               <div class='col-4'>
                   &nbsp;
               </div>
           </div>
           <table class="table table-bordered table-hover">
               <thead>
                   <tr>
                       <th class="col-3">Branch</th>
                       <th class="text-center col-2">Last SOA Created</th>
                       <th class="text-center col-2"># of w/o Transmittal</th>
                       <th class="text-center col-2">Latest Due w/o Tras.</th>
                       <th class="text-center col-2"># w/o Paid</th>
                   </tr>
               </thead>
               <tbody>
                   @foreach ($locaitonList as $list)
                       <tr>
                           <td>{{ $list->NAME }}</td>
                           <td></td>
                           <td></td>
                           <td></td>
                           <td></td>
                       </tr>
                   @endforeach
               </tbody>
           </table>

       </div>

   </div>
