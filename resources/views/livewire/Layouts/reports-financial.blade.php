<li id="financial" class="nav-item {{ request()->is('reports/financial*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('reports/financial*') ? 'active' : '' }}">
        <i class="fa fa-file-text-o  nav-icon"></i>
        <p>
            Financial
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @can('report.financial.income-statement')
            <li class="nav-item ">
                <a href="{{ route('reportsfinancialincome_statement_report') }}"
                    class="nav-link {{ request()->is('reports/financial/income-statement*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Income Statement</p>
                </a>
            </li>
        @endcan
        @can('report.financial.balance-sheet')
            <li class="nav-item">
                <a href="{{ route('reportsfinancialbalance_sheet_report') }}"
                    class="nav-link {{ request()->is('reports/financial/balance-sheet*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Balance Sheet</p>
                </a>
            </li>
        @endcan
        @can('report.financial.cash-flow')
            <li class="nav-item">
                <a href="{{ route('reportsfinancialcash_flow_report') }}"
                    class="nav-link {{ request()->is('reports/financial/cash-flow*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Cash Flow</p>
                </a>
            </li>
        @endcan
    </ul>
</li>
