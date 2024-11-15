<li class="nav-item {{ request()->is('banking*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('banking*') ? 'active ' : '' }}">
        <i class="nav-icon fa fa-university"></i>
        <p> Banking <i class="fas fa-angle-left right"></i> </p>
    </a>
    <ul class="nav nav-treeview bg-blue-dark">
        @can('banking.deposit.view')
            <li class="nav-item">
                <a href="{{ route('bankingdeposit') }}"
                    class="nav-link  {{ request()->is('banking/deposit*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-balance-scale nav-icon"></i>
                    <p>Deposit</p>
                </a>
            </li>
        @endcan
        @can('banking.fund-transfer.view')
            <li class="nav-item">
                <a href="{{ route('bankingfund_transfer') }}"
                    class="nav-link  {{ request()->is('banking/fund-transfer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-exchange nav-icon"></i>
                    <p>Fund Transfer</p>
                </a>
            </li>
        @endcan

        @can('banking.make-cheque.view')
            <li class="nav-item">
                <a href="{{ route('bankingmake_cheque') }}"
                    class="nav-link  {{ request()->is('banking/make-cheque*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="far fa-credit-card nav-icon"></i>
                    <p>Pay by Check</p>
                </a>
            </li>
        @endcan

        @can('banking.bank-recon.view')
            <li class="nav-item">
                <a href="{{ route('bankingbank_recon') }}"
                    class="nav-link  {{ request()->is('banking/bank-recon*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-pencil-square-o nav-icon"></i>
                    <p>Reconciliation</p>
                </a>
            </li>
        @endcan

    </ul>
</li>
