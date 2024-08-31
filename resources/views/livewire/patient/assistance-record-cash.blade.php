          <div style="max-height: 60vh; overflow-y: auto;">
              <table class="table table-sm table-bordered table-hover">
                  <thead class="bg-sky text-xs">
                      <tr>

                          <th class="col-2 bg-success">AR/OR Number</th>
                          <th class="bg-success text-right col-1">Cash</th>
                          <th class="col-1 bg-success"> (P)Code</th>
                          <th class="col-1 bg-success"> (P)Date</th>
                          <th class="col-1 bg-info">(SC)Code</th>
                          <th class="col-1 bg-info">(SC)Date</th>
                          <th class="bg-info">Item Name</th>
                          <th class="bg-info text-right col-1">Usage</th>
                          <th class="text-right col-1 bg-danger">
                              Balance <i wire:click='reload()' type="button" class="fa fa-refresh" aria-hidden="true">
                          </th>
                      </tr>
                  </thead>
                  <tbody class="text-xs">
                      @foreach ($dataList as $list)
                          @php
                              $AMOUNT = $list->DEPOSIT_AMOUNT > 0 ? $list->DEPOSIT_AMOUNT : $list->CREDIT_AMOUNT;
                              $BALANCE = $BALANCE + $AMOUNT;
                          @endphp
                          <tr>
                              <td>{{ $list->DEPOSIT_AMOUNT > 0 ? $list->TRANS_CODE : '' }}</td>
                              <td class="text-right">
                                  {{ $list->DEPOSIT_AMOUNT > 0 ? number_format($list->DEPOSIT_AMOUNT, 2) : '' }}</td>
                              <td>
                                  @if ($list->DEPOSIT_AMOUNT > 0)
                                      <a target="_BLANK"
                                          href="{{ route('patientspayment_edit', ['id' => $list->TRANS_ID]) }}">
                                          {{ $list->P_CODE }}
                                      </a>
                                  @endif
                              </td>
                              <td>{{ $list->DEPOSIT_AMOUNT > 0 ? date('m/d/Y', strtotime($list->P_DATE)) : '' }}</td>
                              <td>
                                  @if ($list->DEPOSIT_AMOUNT == 0)
                                      <a target="_BLANK"
                                          href="{{ route('patientsservice_charges_edit', ['id' => $list->TRANS_ID]) }}">
                                          {{ $list->TRANS_CODE }}
                                      </a>
                                  @endif
                              </td>
                              <td>{{ $list->DEPOSIT_AMOUNT == 0 ? date('m/d/Y', strtotime($list->TRANS_DATE)) : '' }}
                              </td>
                              <td>{{ $list->ITEM_NAME }}</td>
                              <td class="text-right">
                                  {{ $list->DEPOSIT_AMOUNT == 0 ? number_format($list->AMOUNT_APPLIED, 2) : '' }}</td>
                              <td class="text-right">{{ number_format($BALANCE, 2) }}</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>
