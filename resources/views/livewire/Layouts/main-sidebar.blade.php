<aside class="main-sidebar  sidebar-light-info elevation-4 text-xs">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light"><b>E-</b>System</span>
    </a>
    <div class="sidebar">
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                @if (Auth::user()->can('patient.schedule.view') ||
                        Auth::user()->can('patient.service-charges.view') ||
                        Auth::user()->can('patient.payment.view') ||
                        Auth::user()->can('patient.treatment.view') ||
                        Auth::user()->can('patient.philhealth.view'))
                    @livewire('Layouts.PatientMenu')
                @endif
                @if (Auth::user()->can('customer.invoice.view') ||
                        Auth::user()->can('customer.sales-order.view') ||
                        Auth::user()->can('customer.credit-memo.view') ||
                        Auth::user()->can('customer.received-payment.view') ||
                        Auth::user()->can('customer.statement'))
                    @livewire('Layouts.CustomerMenu')
                @endif
                @if (Auth::user()->can('vendor.purchase-order.view') ||
                        Auth::user()->can('vendor.bill.view') ||
                        Auth::user()->can('vendor.bill-credit.view') ||
                        Auth::user()->can('vendor.bill-payment.view'))
                    @livewire('Layouts.VendorMenu')
                @endif
                @if (Auth::user()->can('company.stock-transfer.view') ||
                        Auth::user()->can('company.build-assembly.view') ||
                        Auth::user()->can('company.inventory-adjustment.view') ||
                        Auth::user()->can('company.general-journal.view'))
                    @livewire('Layouts.CompanyMenu')
                @endif
                @if (Auth::user()->can('banking.deposit.view') ||
                        Auth::user()->can('banking.fund-transfer.view') ||
                        Auth::user()->can('banking.make-cheque.view'))
                    @livewire('Layouts.BankingMenu')
                @endif





                {{-- 
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
                                <p> Accounting <i class="right fas fa-angle-left"></i> </p>
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
                </li> --}}

                <li class="nav-item {{ request()->is('maintenance*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('maintenance*') ? 'active ' : '' }}">
                        <i class="nav-icon fa fa-cog"></i>
                        <p>
                            Maintenance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>


                    <ul class="nav nav-treeview">
                        @if (Auth::user()->can('contact.customer.view') ||
                                Auth::user()->can('contact.vendor.view') ||
                                Auth::user()->can('contact.employee.view') ||
                                Auth::user()->can('contact.patient.view') ||
                                Auth::user()->can('contact.doctor.view'))
                            <li class="nav-item {{ request()->is('maintenance/contact*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('maintenance/contact*') ? 'active' : '' }}">
                                    <i class="fa fa-address-book nav-icon"></i>
                                    <p>
                                        Contacts
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @can('contact.customer.view')
                                        <li class="nav-item">
                                            <a href="{{ route('maintenancecontactcustomer') }}"
                                                class="nav-link {{ request()->is('maintenance/contact/customer*') ? 'active' : '' }}">
                                                <i class="fa fa-file nav-icon"></i>
                                                <p>Customer</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('contact.vendor.view')
                                        <li class="nav-item">
                                            <a href="{{ route('maintenancecontactvendor') }}"
                                                class="nav-link {{ request()->is('maintenance/contact/vendor*') ? 'active' : '' }}">
                                                <i class="fa fa-file nav-icon"></i>
                                                <p>Vendor</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('contact.employee.view')
                                        <li class="nav-item">
                                            <a href="{{ route('maintenancecontactemployees') }}"
                                                class="nav-link {{ request()->is('maintenance/contact/employees*') ? 'active' : '' }}">
                                                <i class="fa fa-file nav-icon"></i>
                                                <p>Employees</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('contact.patient.view')
                                        <li class="nav-item">
                                            <a href="{{ route('maintenancecontactpatients') }}"
                                                class="nav-link {{ request()->is('maintenance/contact/patients*') ? 'active' : '' }}">
                                                <i class="fa fa-file nav-icon"></i>
                                                <p>Patients</p>
                                            </a>
                                        </li>
                                    @endcan

                                    @can('contact.doctor.view')
                                        <li class="nav-item">
                                            <a href="{{ route('maintenancecontactdoctors') }}"
                                                class="nav-link {{ request()->is('maintenance/contact/doctors*') ? 'active' : '' }}">
                                                <i class="fa fa-file nav-icon"></i>
                                                <p>Doctors</p>
                                            </a>
                                        </li>
                                    @endcan



                                </ul>
                            </li>
                        @endif

                        <li class="nav-item {{ request()->is('maintenance/financial*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/financial*') ? 'active' : '' }}">
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
                                class="nav-link {{ request()->is('maintenance/inventory*') ? 'active' : '' }}">
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
                                        <i class="fa fa-cube nav-icon"></i>
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
                        <li class="nav-item {{ request()->is('maintenance/others*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/others*') ? 'active' : '' }}">
                                <i class="fa fa-list-alt nav-icon" aria-hidden="true"></i>
                                <p>
                                    Others
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceothersshift') }}"
                                        class="nav-link {{ request()->is('maintenance/others/shift*') ? 'active' : '' }}">
                                        <i class="fa fa-file nav-icon"></i>
                                        <p>Shift</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceothershemo_machine') }}"
                                        class="nav-link {{ request()->is('maintenance/others/hemodialysis-machine*') ? 'active' : '' }}">
                                        <i class="fa fa-sort-numeric-asc nav-icon"></i>
                                        <p>Hemodialysis Machine</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceothersrequirement') }}"
                                        class="nav-link {{ request()->is('maintenance/others/requirement*') ? 'active' : '' }}">
                                        <i class="fa fa-sort-numeric-asc nav-icon"></i>
                                        <p>Rquirements</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('maintenanceothersitem-active-list') }}"
                                        class="nav-link {{ request()->is('maintenance/others/item-active-list*') ? 'active' : '' }}">
                                        <i class="fa fa-sort-numeric-asc nav-icon"></i>
                                        <p>Active items</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('maintenanceothersitem_treatment') }}"
                                        class="nav-link {{ request()->is('maintenance/others/item-treatment*') ? 'active' : '' }}">
                                        <i class="fa fa-sort-numeric-asc nav-icon"></i>
                                        <p>Item Treatment</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('maintenance/settings*') ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ request()->is('maintenance/settings*') ? 'active' : '' }}">
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
                                    <a href="{{ route('maintenancesettingsoption') }}"
                                        class="nav-link {{ request()->is('maintenance/settings/option*') ? 'active' : '' }}">
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
