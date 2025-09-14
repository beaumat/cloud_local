<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('customersstatement') }}"> Statement of Account </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pb-1">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-6 row">
                                                <div class="col-2 text-right">NAME :</div>
                                                <div class="col-10 font-weight-bold">{{ $NAME }}</div>
                                                <div class="col-2 text-right">TYPE :</div>
                                                <div class="col-10 font-weight-bold">{{ $CONTACT_TYPE }}</div>

                                            </div>
                                            <div class="col-6 row ">
                                                <div class="col-2 text-right">Previous Balance :</div>
                                                <div class="col-10">{{ number_format($PREV_BALANCE, 2) }}</div>
                                                <div class="col-2 text-right">Total Debit :</div>
                                                <div class="col-10">{{ number_format($TOTAL_DEBIT, 2) }}</div>
                                                <div class="col-2 text-right">Total Credit :</div>
                                                <div class="col-10">{{ number_format($TOTAL_CREDIT, 2) }}</div>
                                                <div class="col-2 text-right">Balance Due :</div>
                                                <div class="col-10">{{ number_format($BALANCE_DUE, 2) }}</div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1 text-left">Date</th>
                                        <th class="col-1 text-left">Type</th>
                                        <th class="col-1 text-left">Ref#</th>
                                        <th class="col-1 text-left">Location</th>
                                        <th>Description</th>
                                        <th class="col-1">Debit</th>
                                        <th class="col-1">Credit</th>
                                        <th class="col-1">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @php
                                        $BALANCE = $PREV_BALANCE;
                                    @endphp
                                    @if ($BALANCE > 0)
                                        <tr>
                                            <td> </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Previous Balance</td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">

                                            </td>
                                            <td class="text-right">{{ number_format($BALANCE, 2) }}</td>
                                        </tr>
                                    @endif
                                    @foreach ($dataList as $list)
                                        @php
                                            $BALANCE = $BALANCE + $list->AMT;
                                        @endphp


                                        <tr>
                                            <td> {{ date('M d, Y', strtotime($list->DATE)) }}</td>
                                            <td>{{ $list->TYPE }}</td>
                                            <td>{{ $list->CODE }}</td>
                                            <td>{{ $list->LOCATION }}</td>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td class="text-right">
                                                @if ($list->ENTRY_TYPE == 0)
                                                    {{ number_format($list->AMOUNT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($list->ENTRY_TYPE != 0)
                                                    {{ number_format($list->AMOUNT, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ number_format($BALANCE, 2) }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
