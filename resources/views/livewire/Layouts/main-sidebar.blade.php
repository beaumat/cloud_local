<aside class="main-sidebar  sidebar-light-info elevation-1 text-xs">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/cloud_128.png') }}" alt="" class="brand-image elevation-0" style="opacity: .8">

        <span class="brand-text font-weight-light text-xs text-info"><b>Cloud</b> System.</span>
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
                        Auth::user()->can('company.general-journal.view') ||
                        Auth::user()->can('company.pull-out.view'))
                    @livewire('Layouts.CompanyMenu')
                @endif
                @if (Auth::user()->can('banking.deposit.view') ||
                        Auth::user()->can('banking.fund-transfer.view') ||
                        Auth::user()->can('banking.make-cheque.view'))
                    @livewire('Layouts.BankingMenu')
                @endif
                @if (Auth::user()->can('report.patient.sales'))
                    <li class="nav-item {{ request()->is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}"> <i
                                class="nav-icon fa fa-line-chart"></i>
                            <p> Reports <i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (Auth::user()->can('report.patient.sales'))
                                @livewire('Layouts.ReportsPatients')
                            @endif

                            {{-- @livewire('Layouts.ReportsFinancial')
                            @livewire('Layouts.ReportsSales')
                            @livewire('Layouts.ReportsReceivables')
                            @livewire('Layouts.ReportsPurchases')
                            @livewire('Layouts.ReportsExpenses')
                            @livewire('Layouts.ReportsPayables')
                            @livewire('Layouts.ReportsInventory')
                            @livewire('Layouts.ReportsAccounting')
                            @livewire('Layouts.ReportsDocuments') --}}
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->can('contact.customer.view') ||
                        Auth::user()->can('contact.vendor.view') ||
                        Auth::user()->can('contact.employee.view') ||
                        Auth::user()->can('contact.patient.view') ||
                        Auth::user()->can('contact.doctor.view') ||
                        Auth::user()->can('chart-of-account.view') ||
                        Auth::user()->can('payment-method.view') ||
                        Auth::user()->can('payment-term.view') ||
                        Auth::user()->can('tax-list.view') ||
                        Auth::user()->can('items.view') ||
                        Auth::user()->can('item-class.view') ||
                        Auth::user()->can('item-sub-class.view') ||
                        Auth::user()->can('item-group.view') ||
                        Auth::user()->can('manufacturer.view') ||
                        Auth::user()->can('ship-via.view') ||
                        Auth::user()->can('unit-of-measure.view') ||
                        Auth::user()->can('inventory-adjustment-type.view') ||
                        Auth::user()->can('stock-bin.view') ||
                        Auth::user()->can('price-level.view') ||
                        Auth::user()->can('others.shift.view') ||
                        Auth::user()->can('others.hemodialysis-machine.view') ||
                        Auth::user()->can('others.requirement.view') ||
                        Auth::user()->can('others.item-active-list.view') ||
                        Auth::user()->can('others.item-treatment.view') ||
                        Auth::user()->can('users') ||
                        Auth::user()->can('roles-and-permission') ||
                        Auth::user()->can('location.view') ||
                        Auth::user()->can('location-group.view') ||
                        Auth::user()->can('option'))


                    <li class="nav-item {{ request()->is('maintenance*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('maintenance*') ? 'active ' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p> Maintenance <i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @if (Auth::user()->can('contact.customer.view') ||
                                    Auth::user()->can('contact.vendor.view') ||
                                    Auth::user()->can('contact.employee.view') ||
                                    Auth::user()->can('contact.patient.view') ||
                                    Auth::user()->can('contact.doctor.view'))
                                @livewire('Layouts.MaintenanceContacts')
                            @endif
                            @if (Auth::user()->can('chart-of-account.view') ||
                                    Auth::user()->can('payment-method.view') ||
                                    Auth::user()->can('payment-term.view') ||
                                    Auth::user()->can('tax-list.view'))
                                @livewire('Layouts.MaintenanceFinancials')
                            @endif
                            @if (Auth::user()->can('items.view') ||
                                    Auth::user()->can('item-class.view') ||
                                    Auth::user()->can('item-sub-class.view') ||
                                    Auth::user()->can('item-group.view') ||
                                    Auth::user()->can('manufacturer.view') ||
                                    Auth::user()->can('ship-via.view') ||
                                    Auth::user()->can('unit-of-measure.view') ||
                                    Auth::user()->can('inventory-adjustment-type.view') ||
                                    Auth::user()->can('stock-bin.view') ||
                                    Auth::user()->can('price-level.view'))
                                @livewire('Layouts.MaintenanceInventory')
                            @endif
                            @if (Auth::user()->can('others.shift.view') ||
                                    Auth::user()->can('others.hemodialysis-machine.view') ||
                                    Auth::user()->can('others.requirement.view') ||
                                    Auth::user()->can('others.item-active-list.view') ||
                                    Auth::user()->can('others.item-treatment.view'))
                                @livewire('Layouts.MaintenanceOthers')
                            @endif
                            @if (Auth::user()->can('users') ||
                                    Auth::user()->can('roles-and-permission') ||
                                    Auth::user()->can('location.view') ||
                                    Auth::user()->can('location-group.view') ||
                                    Auth::user()->can('option'))
                                @livewire('Layouts.MaintenanceSettings')
                            @endif

                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
