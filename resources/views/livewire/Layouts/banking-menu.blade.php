
   
            <li class="nav-item {{ request()->is('banking*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('banking*') ? 'active ' : '' }}">
                    <i class="nav-icon fa fa-university"></i>
                    <p> Banking <i class="fas fa-angle-left right"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('banking.deposit.view')
                        <li class="nav-item">
                            <a href="{{ route('bankingdeposit') }}"
                                class="nav-link  {{ request()->is('banking/deposit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deposit</p>
                            </a>
                        </li>
                    @endcan
                    @can('banking.fund-transfer.view')
                        <li class="nav-item">
                            <a href="{{ route('bankingfund_transfer') }}"
                                class="nav-link  {{ request()->is('banking/fund-transfer*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fund Transfer</p>
                            </a>
                        </li>
                    @endcan
                    @can('banking.make-cheque.view')
                        <li class="nav-item">
                            <a href="{{ route('bankingmake_cheque') }}"
                                class="nav-link  {{ request()->is('banking/make-cheque*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Make Cheque</p>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

