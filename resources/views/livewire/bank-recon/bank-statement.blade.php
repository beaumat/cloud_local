   <table class="table table-sm table-bordered table-hover">
       <thead class="text-xs bg-primary">
           <tr>
               <th class="col-1">DATE TRANSACTION</th>
               <th class="col-1">REFERENCE</th>
               <th class="col-1">DESCRIPTION</th>
               <th class="col-1">CHECK NUMBER</th>
               <th class="col-1">DEBIT</th>
               <th class="col-1">CREDIT</th>
               <th class="col-1">BALANCE</th>
               <th class="col-1 bg-purple">DOC TYPE</th>
               <th class="col-1 bg-purple">DOC REF#</th>
               <th class="col-1 bg-purple">AMOUNT</th>
               <th class="col-1 bg-purple">LOCATION</th>
               <th class="col-1 bg-success"> <button class="btn btn-xs btn-success w-100">Auto match</button></th>
           </tr>
       </thead>
       <tbody class="text-xs">
           @foreach ($dataList as $list)
               <tr>
                   <td> {{ date('m/d/Y H:i:s', strtotime($list->DATE_TRANSACTION)) }}</td>
                   <td>{{ $list->REFERENCE }}</td>
                   <td>{{ $list->DESCRIPTION }}</td>
                   <td>{{ $list->CHECK_NUMBER }}</td>
                   <td>
                       @if ($list->DEBIT > 0)
                           {{ number_format($list->DEBIT, 2) }}
                       @endif
                   </td>
                   <td>
                       @if ($list->CREDIT > 0)
                           {{ number_format($list->CREDIT, 2) }}
                       @endif
                   </td>
                   <td>
                       @if ($list->BALANCE > 0)
                           {{ number_format($list->BALANCE, 2) }}
                       @endif
                   </td>
                   <td>


                   </td>
                   <td>


                   </td>
                   <td>


                   </td>
                   <td>


                   </td>
                   <td>
                       <button class="btn btn-xs btn-primary"> Find</button>

                   </td>
               </tr>
           @endforeach
       </tbody>
   </table>
