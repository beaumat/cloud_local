<li class="nav-item {{ request()->is('company*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('company*') ? 'active ' : '' }}">
        <i class="nav-icon fa fa-building"></i>
        <p> Company <i class="fas fa-angle-left right"></i> </p>
    </a>
    <ul class="nav nav-treeview bg-blue-dark">
        @can('company.stock-transfer.view')
            <li class="nav-item">
                <a href="{{ route('companystock_transfer') }}"
                    class="nav-link {{ request()->is('company/stock-transfer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-truck nav-icon"></i>
                    <p>Stock Transfer</p>
                </a>
            </li>
        @endcan
        @can('company.inventory-adjustment.view')
            <li class="nav-item">
                <a href="{{ route('companyinventory_adjustment') }}"
                    class="nav-link {{ request()->is('company/inventory-adjustment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-adjust nav-icon"></i>
                    <p>Inventory Adjustment</p>
                </a>
            </li>
        @endcan
        @if(Auth()->user()->can('company.build-assembly.view'))
            <li class="nav-item">
                <a href="{{ route('companybuild_assembly') }}"
                    class="nav-link {{ request()->is('company/build-assembly*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Build Assembly</p>
                </a>
            </li>
        @endif
        @can('company.pull-out.view')
            <li class="nav-item">
                <a href="{{ route('companypull_out') }}"
                    class="nav-link {{ request()->is('company/pull-out*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-undo nav-icon"></i>
                    <p>Pull Out</p>
                </a>
            </li>
        @endcan
        
        @can('company.general-journal.view')
            <li class="nav-item">
                <a href="{{ route('companygeneral_journal') }}"
                    class="nav-link  {{ request()->is('company/general-journal*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-table nav-icon"></i>
                    <p>General Journal</p>
                </a>
            </li>
        @endcan





    </ul>
</li>
