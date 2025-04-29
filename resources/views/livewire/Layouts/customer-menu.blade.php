<li class="nav-item {{ request()->is('customers*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('customers*') ? 'active ' : '' }}">
        <i class="nav-icon fas fa-users "></i>
        <p> Customers <i class="fas fa-angle-left right"></i> </p>
    </a>

    <ul class="nav nav-treeview bg-blue-dark">
        @can('customer.sales-order.view')
            <li class="nav-item">
                <a href="{{ route('customerssales_order') }}"
                    class="nav-link {{ request()->is('customers/sales-order*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-truck nav-icon"></i>
                    <p>Sales Order</p>
                </a>
            </li>
        @endcan
        @can('customer.invoice.view')
            <li class="nav-item">
                <a href="{{ route('customersinvoice') }}"
                    class="nav-link {{ request()->is('customers/invoice*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-sticky-note nav-icon"></i>
                    <p>Invoice</p>
                </a>
            </li>
        @endcan
        @can('customer.received-payment.view')
            <li class="nav-item">
                <a href="{{ route('customerspayment') }}"
                    class="nav-link {{ request()->is('customers/payment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-money nav-icon"></i>
                    <p>Receive Payment</p>
                </a>
            </li>
        @endcan
        @can('customer.credit-memo.view')
            <li class="nav-item">
                <a href="{{ route('customerscredit_memo') }}"
                    class="nav-link {{ request()->is('customers/credit-memo*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-envelope nav-icon"></i>
                    <p>Credit Memo</p>
                </a>
            </li>
        @endcan
        @can('customer.tax-credit.view')
            <li class="nav-item">
                <a href="{{ route('customerstax_credit') }}"
                    class="nav-link {{ request()->is('customers/tax-credit*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-university nav-icon"></i>
                    <p>Tax Credit</p>
                </a>
            </li>
        @endcan
        @can('customer.invoice.view')
            <li class="nav-item">
                <a href="{{ route('customerssales_receipt') }}"
                    class="nav-link {{ request()->is('customers/sales-receipt*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-sticky-note nav-icon"></i>
                    <p>Sales Receipt</p>
                </a>
            </li>
        @endcan

        @can('customer.statement')
            <li class="nav-item">
                <a href="{{ route('customersstatement') }}"
                    class="nav-link {{ request()->is('customers/statment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-bar-chart nav-icon"></i>
                    <p>Statement</p>
                </a>
            </li>
        @endcan



    </ul>
</li>
