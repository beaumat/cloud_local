<aside class="main-sidebar  sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light"><b>X</b>ONE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="pb-3 mt-3 mb-3 user-panel d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/user2.jpg') }}" class="img-circle text-sm elevation-2" alt="User Image">
            </div>
            <div class="info text-sm">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

        <!-- Sidebar Menu -->
        <nav class="mt-1 text-xs">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users "></i>
                        <p>
                            Patients
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Receipt</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Credit Memo</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tax Credit</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Statement of Account</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Suppliers
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bills</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bill Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bill Credits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Withholding Tax</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-building"></i>
                        <p>
                            Company
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Build Assembly</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>General Journal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inventory Adjustment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pages/UI/sliders.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stock Transfer</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-university"></i>
                        <p>
                            Banking
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deposit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fund Transfer</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Make Cheque</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-line-chart"></i>
                        <p>
                            Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o  nav-icon"></i>
                                <p>
                                    Financial
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item ">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Sales
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Receivables
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Purchases
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Expenses
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Payables
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Inventory
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Accounting
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-file-text-o nav-icon"></i>
                                <p>
                                    Documents
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-print  nav-icon"></i>
                                        <p>Level 3</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ request()->is('maintenance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('maintenance*') ? 'active ' : '' }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Maintenance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item {{ request()->is('maintenance/contact*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/contact*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-address-book nav-icon"></i>
                                <p>
                                    Contacts
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Patients</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenancecontactsupplier') }}"
                                    class="nav-link {{ request()->is('maintenance/contact/supplier*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Suppliers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Employees</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Doctors</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('maintenance/financial*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/financial*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-pie-chart nav-icon"></i>
                                <p>
                                    Financial
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('maintenancefinancialcoa') }}"
                                        class="nav-link {{ request()->is('maintenance/financial/chart-of-account*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Chart Of Account</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Class</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenancefinancialpayment_method') }}"
                                        class="nav-link {{ request()->is('maintenance/financial/payment-method*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Payment Methods</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenancefinancialpayment_term') }}"
                                        class="nav-link {{ request()->is('maintenance/financial/payment-term*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Payment Terms</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenancefinancialtax_list') }}"
                                        class="nav-link {{ request()->is('maintenance/financial/tax-list*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Tax List</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('maintenance/inventory*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/inventory*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-cubes nav-icon"></i>
                                <p>
                                    Inventory
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryitem') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/items*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Items</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryitem_group') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/item-group*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Item Group</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryitem_class') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/item-class*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Item Class</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryitem_sub_class') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/item-sub-class*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Item Sub Class</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventorymanufacturers') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/manufacturers*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Manufacturers</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryship_via') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/ship-via*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Ship Via</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryprice_level') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/price-level*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Price Level</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryunit_of_measure') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/unit-of-measure*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Unit of Measure</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventorystock_bin') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/stock-bin*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Stock Bin</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceinventoryinventory_adjustment_type') }}"
                                        class="nav-link {{ request()->is('maintenance/inventory/inventory-adjustment-type*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Inventory Adjustment Type</p>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item {{ request()->is('maintenance/settings*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/settings*') ? 'active text-primary' : '' }}">
                                <i class="fa fa-wrench nav-icon"></i>
                                <p>
                                    Settings
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('users')
                                    <li class="nav-item">
                                        <a href="{{ route('maintenancesettingsusers') }}"
                                            class="nav-link {{ request()->is('maintenance/settings/user*') ? 'active' : '' }}">
                                            <i class="fa fa-file nav-icon"></i>
                                            <p>Users</p>
                                        </a>
                                    </li>
                                @endcan
                                <li class="nav-item">
                                    <a href="{{ route('maintenancesettingsroles') }}"
                                        class="nav-link {{ request()->is('maintenance/settings/rolespermission*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Roles & Permission</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('maintenancesettingslocation') }}"
                                        class="nav-link {{ request()->is('maintenance/settings/location*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Location</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('maintenancesettingslocation_group') }}"
                                        class="nav-link {{ request()->is('maintenance/settings/location-group*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Location Group</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Options</p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

    </div>
</aside>
