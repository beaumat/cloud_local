<div class="row">
    <div class="col-md-10">
        <table class="table table-sm text-xs table-bordered ">
            <thead>
                <tr class="text-center">
                    <th style="width: 25%;" class="bg-success">PARTICULARS</th>
                    <th style="width: 10%;" class="bg-primary">ACTUAL CHARGES</th>
                    <th style="width: 10%;" class="bg-info">VAT EXEMPT</th>
                    <th style="width: 10%;" class="bg-info">SENIOR CITIZEN/ PWD</th>
                    <th style="width: 10%;" class="bg-info">
                        <ul style="list-style: none; padding: 0; margin: 0;" class="text-center">
                            <li>PCSO</li>
                            <li>DSWD</li>
                            <li>DOH</li>
                            <li>HMO</li>
                            <li>LINGAP</li>
                        </ul>
                    </th>
                    <th style="width: 5%;" class="bg-info">AMOUNT AFTER DISCOUNT</th>
                    <th style="width: 10%;" class="bg-warning">First Case Rate Amount</th>
                    <th style="width: 10%;" class="bg-warning">Second Case Rate Amount</th>
                    <th style="width: 10%;" class="bg-secondary">Out of Pocket of Patients</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-weight-bold"><label class="text-sm">HCI Fee</label></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Room and Board</td>
                    <td class="text-right">
                        {{-- <livewire:number name='CHARGES_ROOM_N_BOARD' wire:model.live.lazy='CHARGES_ROOM_N_BOARD' /> --}}
                        @if ($CHARGES_ROOM_N_BOARD > 0)
                            {{ number_format($CHARGES_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='VAT_ROOM_N_BOARD' wire:model.live.lazy='VAT_ROOM_N_BOARD' /> --}}
                        @if ($VAT_ROOM_N_BOARD > 0)
                            {{ number_format($VAT_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='SP_ROOM_N_BOARD' wire:model.live.lazy='SP_ROOM_N_BOARD' /> --}}
                        @if ($SP_ROOM_N_BOARD > 0)
                            {{ number_format($SP_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='GOV_ROOM_N_BOARD' wire:model.live.lazy='GOV_ROOM_N_BOARD' /> --}}

                        @if ($GOV_ROOM_N_BOARD > 0)
                            {{ number_format($GOV_ROOM_N_BOARD, 2) }}
                        @endif
                    </td>

                    <td>
                        {{-- <livewire:number name='P1_ROOM_N_BOARD' wire:model.live.lazy='P1_ROOM_N_BOARD' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_ROOM_N_BOARD' wire:model.live.lazy='P2_ROOM_N_BOARD' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_ROOM_N_BOARD' wire:model.live.lazy='OP_ROOM_N_BOARD' /> --}}
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td class="text-left">Drug & Medicines</td>
                    <td class="text-right">
                        {{-- <livewire:number name='CHARGES_DRUG_N_MEDICINE' wire:model.live.lazy='CHARGES_DRUG_N_MEDICINE' /> --}}
                        @if ($CHARGES_DRUG_N_MEDICINE > 0)
                            {{ number_format($CHARGES_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='VAT_DRUG_N_MEDICINE' wire:model.live.lazy='VAT_DRUG_N_MEDICINE' /> --}}
                        @if ($VAT_DRUG_N_MEDICINE > 0)
                            {{ number_format($VAT_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='SP_DRUG_N_MEDICINE' wire:model.live.lazy='SP_DRUG_N_MEDICINE' /> --}}
                        @if ($SP_DRUG_N_MEDICINE > 0)
                            {{ number_format($SP_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='GOV_DRUG_N_MEDICINE' wire:model.live.lazy='GOV_DRUG_N_MEDICINE' /> --}}
                        @if ($GOV_DRUG_N_MEDICINE > 0)
                            {{ number_format($GOV_DRUG_N_MEDICINE, 2) }}
                        @endif
                    </td>
                    <td>
                        {{-- <livewire:number name='P1_DRUG_N_MEDICINE' wire:model.live.lazy='P1_DRUG_N_MEDICINE' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_DRUG_N_MEDICINE' wire:model.live.lazy='P2_DRUG_N_MEDICINE' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_DRUG_N_MEDICINE' wire:model.live.lazy='OP_DRUG_N_MEDICINE' /> --}}
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Laboratory & Diagnostics</td>
                    <td class="text-right">
                        {{-- <livewire:number name='CHARGES_LAB_N_DIAGNOSTICS' wire:model.live.lazy='CHARGES_LAB_N_DIAGNOSTICS' /> --}}
                        @if ($CHARGES_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($CHARGES_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='VAT_LAB_N_DIAGNOSTICS' wire:model.live.lazy='VAT_LAB_N_DIAGNOSTICS' /> --}}
                        @if ($VAT_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($VAT_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='SP_LAB_N_DIAGNOSTICS' wire:model.live.lazy='SP_LAB_N_DIAGNOSTICS' /> --}}
                        @if ($SP_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($SP_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='GOV_LAB_N_DIAGNOSTICS' wire:model.live.lazy='GOV_LAB_N_DIAGNOSTICS' /> --}}
                        @if ($GOV_LAB_N_DIAGNOSTICS > 0)
                            {{ number_format($GOV_LAB_N_DIAGNOSTICS, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='P1_LAB_N_DIAGNOSTICS' wire:model.live.lazy='P1_LAB_N_DIAGNOSTICS' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_LAB_N_DIAGNOSTICS' wire:model.live.lazy='P2_LAB_N_DIAGNOSTICS' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_LAB_N_DIAGNOSTICS' wire:model.live.lazy='OP_LAB_N_DIAGNOSTICS' /> --}}
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="text-left">Operating Room Fees</td>
                    <td class="text-right">
                        {{-- <livewire:number name='CHARGES_OPERATING_ROOM_FEE' wire:model.live.lazy='CHARGES_OPERATING_ROOM_FEE' /> --}}

                        @if ($CHARGES_OPERATING_ROOM_FEE > 0)
                            {{ number_format($CHARGES_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='VAT_OPERATING_ROOM_FEE' wire:model.live.lazy='VAT_OPERATING_ROOM_FEE' /> --}}

                        @if ($VAT_OPERATING_ROOM_FEE > 0)
                            {{ number_format($VAT_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='SP_OPERATING_ROOM_FEE' wire:model.live.lazy='SP_OPERATING_ROOM_FEE' /> --}}

                        @if ($SP_OPERATING_ROOM_FEE > 0)
                            {{ number_format($SP_OPERATING_ROOM_FEE, 2) }}
                        @endif

                    <td class="text-right">
                        {{-- <livewire:number name='GOV_OPERATING_ROOM_FEE' wire:model.live.lazy='GOV_OPERATING_ROOM_FEE' /> --}}

                        @if ($GOV_OPERATING_ROOM_FEE > 0)
                            {{ number_format($GOV_OPERATING_ROOM_FEE, 2) }}
                        @endif
                    </td>
                    <td></td>
                    <td>
                        {{-- <livewire:number name='P1_OPERATING_ROOM_FEE' wire:model.live.lazy='P1_OPERATING_ROOM_FEE' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_OPERATING_ROOM_FEE' wire:model.live.lazy='P2_OPERATING_ROOM_FEE' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_OPERATING_ROOM_FEE' wire:model.live.lazy='OP_OPERATING_ROOM_FEE' /> --}}
                    </td>

                </tr>
                <tr>
                    <td class="text-left">Supplies</td>
                    <td class="text-right">
                        {{-- <livewire:number name='CHARGES_SUPPLIES' wire:model.live.lazy='CHARGES_SUPPLIES' /> --}}
                        @if ($CHARGES_SUPPLIES > 0)
                            {{ number_format($CHARGES_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='VAT_SUPPLIES' wire:model.live.lazy='VAT_SUPPLIES' /> --}}
                        @if ($VAT_SUPPLIES > 0)
                            {{ number_format($VAT_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='SP_SUPPLIES' wire:model.live.lazy='SP_SUPPLIES' /> --}}
                        @if ($SP_SUPPLIES > 0)
                            {{ number_format($SP_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        {{-- <livewire:number name='GOV_SUPPLIES' wire:model.live.lazy='GOV_SUPPLIES' /> --}}
                        @if ($GOV_SUPPLIES > 0)
                            {{ number_format($GOV_SUPPLIES, 2) }}
                        @endif
                    </td>
                    <td></td>
                    <td>
                        {{-- <livewire:number name='P1_SUPPLIES' wire:model.live.lazy='P1_SUPPLIES' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_SUPPLIES' wire:model.live.lazy='P2_SUPPLIES' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_SUPPLIES' wire:model.live.lazy='OP_SUPPLIES' /> --}}
                    </td>

                </tr>

                <tr>
                    <td class="text-left">Others: specify</td>
                    <td>
                        {{-- <livewire:number name='CHARGES_OTHERS' wire:model.live.lazy='CHARGES_OTHERS' /> --}}

                        @if ($CHARGES_OTHERS > 0)
                            {{ number_format($CHARGES_OTHERS, 2) }}
                        @endif
                    </td>
                    <td>
                        {{-- <livewire:number name='' wire:model.live.lazy='VAT_OTHERS' /> --}}

                        @if ($VAT_OTHERS > 0)
                            {{ number_format($VAT_OTHERS, 2) }}
                        @endif
                    </td>
                    <td>
                        {{-- <livewire:number name='SP_OTHERS' wire:model.live.lazy='SP_OTHERS' /> --}}
                        @if ($SP_OTHERS > 0)
                            {{ number_format($SP_OTHERS, 2) }}
                        @endif
                    </td>
                    <td>
                        {{-- <livewire:number name='GOV_OTHERS' wire:model.live.lazy='GOV_OTHERS' /> --}}

                        @if ($GOV_OTHERS > 0)
                            {{ number_format($GOV_OTHERS, 2) }}
                        @endif
                    </td>
                    <td>
                        {{-- <livewire:number name='P1_OTHERS' wire:model.live.lazy='P1_OTHERS' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='P2_OTHERS' wire:model.live.lazy='P2_OTHERS' /> --}}
                    </td>
                    <td>
                        {{-- <livewire:number name='OP_OTHERS' wire:model.live.lazy='OP_OTHERS' /> --}}
                    </td>
                    <td></td>
                </tr>
                <tr class="text-xs">
                    <td class="text-left"><label class="text-xs">SUBTOTAL</label></td>
                    <td class="text-right font-weight-bold ">
                        @if ($CHARGES_SUB_TOTAL > 0)
                            {{ number_format($CHARGES_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($VAT_SUB_TOTAL > 0)
                            {{ number_format($VAT_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($SP_SUB_TOTAL > 0)
                            {{ number_format($SP_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($GOV_SUB_TOTAL > 0)
                            {{ number_format($GOV_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td></td> {{-- after discount --}}
                    <td class="text-right font-weight-bold">
                        @if ($P1_SUB_TOTAL > 0)
                            {{ number_format($P1_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($P2_SUB_TOTAL > 0)
                            {{ number_format($P2_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold ">
                        @if ($OP_SUB_TOTAL > 0)
                            {{ number_format($OP_SUB_TOTAL, 2) }}
                        @endif
                    </td>
                </tr>
                @if ($feeList)
                    <tr>
                        <td class="text-xs text-info">Professional Fee/s</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    @foreach ($feeList as $list)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <td>{{ $i . '.)' }} {{ $list->NAME }} </td>
                            <td class="text-right font-weight-bold text-xs ">
                                @if ($list->AMOUNT > 0)
                                    {{ number_format($list->AMOUNT, 2) }}
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="discount"></td>
                            <td class="text-right font-weight-bold text-xs ">
                                @if ($list->AMOUNT > 0)
                                    {{ number_format($list->AMOUNT, 2) }}
                                @endif
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach


                @endif

                <tr>
                    <td class="text-left">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold ">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold ">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                    <td class="text-right font-weight-bold">
                        &nbsp;
                    </td>
                </tr>
                <tr class="text-primary text-xs">
                    <td class="text-left"><label>TOTAL</label></td>
                    <td class="text-right font-weight-bold ">
                        @if ($CHARGE_TOTAL > 0)
                            {{ number_format($CHARGE_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($VAT_TOTAL > 0)
                            {{ number_format($VAT_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($SP_TOTAL > 0)
                            {{ number_format($SP_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold ">
                        @if ($GOV_TOTAL > 0)
                            {{ number_format($GOV_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if (0 > 0)
                            {{ number_format(0, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($P1_TOTAL > 0)
                            {{ number_format($P1_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($P2_TOTAL > 0)
                            {{ number_format($P2_TOTAL, 2) }}
                        @endif
                    </td>
                    <td class="text-right font-weight-bold">
                        @if ($OP_TOTAL > 0)
                            {{ number_format($CHARGE_TOTAL - $P1_TOTAL, 2) }}
                        @endif
                    </td>

                </tr>
            </tbody>
        </table>
    </div>
</div>
