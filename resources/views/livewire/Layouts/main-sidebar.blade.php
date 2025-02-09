<aside class="main-sidebar  sidebar-dark-info elevation-1 text-xs">
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
                        Auth::user()->can('vendor.bill-payment.view') ||
                        AUth::user()->can('vendor.withholding-tax.view'))
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
                        Auth::user()->can('banking.make-cheque.view') ||
                        Auth::user()->can('banking.bank-recon.view'))
                    @livewire('Layouts.BankingMenu')
                @endif
                @if (Auth::user()->can('report.patient.sales') ||
                        Auth::user()->can('report.patient.treatment') ||
                        Auth::user()->can('report.patient.balance') ||
                        Auth::user()->can('report.patient.doctor-pf') ||
                        Auth::user()->can('report.financial.income-statement') ||
                        Auth::user()->can('report.financial.balance-sheet') ||
                        Auth::user()->can('report.financial.cash-flow') ||
                        Auth::user()->can('report.accounting.general-ledger') ||
                        Auth::user()->can('report.accounting.trial-balance') ||
                        Auth::user()->can('report.accounting.transaction-details') ||
                        Auth::user()->can('report.accounting.transaction-journal') ||
                        Auth::user()->can('report.customer.sales'))

                    <li class="nav-item {{ request()->is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-line-chart"></i>
                            <p> Reports <i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview bg-blue-dark">
                            @if (Auth::user()->can('report.patient.sales') ||
                                    Auth::user()->can('report.patient.treatment') ||
                                    Auth::user()->can('report.patient.balance') ||
                                    Auth::user()->can('report.patient.doctor-pf'))
                                @livewire('Layouts.ReportsPatients')
                            @endif

                            @if (Auth::user()->can('report.customer.sales'))
                                @livewire('Layouts.ReportsSales')
                            @endif

                            @if (Auth::user()->can('report.accounting.general-ledger') ||
                                    Auth::user()->can('report.accounting.trial-balance') ||
                                    Auth::user()->can('report.accounting.transaction-journal') ||
                                    Auth::user()->can('report.accounting.transaction-details'))
                                @livewire('Layouts.ReportsAccounting')
                            @endif

                            @if (Auth::user()->can('report.financial.income-statement') ||
                                    Auth::user()->can('report.financial.balance-sheet') ||
                                    Auth::user()->can('report.financial.cash-flow'))
                                @livewire('Layouts.ReportsFinancial')
                            @endif
                            {{-- Receivables --}}

                            @if (Auth::user()->can('report.receivables.ar-aging') || Auth::user()->can('report.receivables.customer-balance'))
                                @livewire('Layouts.ReportsReceivables')
                            @endif
                            {{-- Payables --}}
                            @if (Auth::user()->can('report.payables.ap-aging') || Auth::user()->can('report.payables.vendor-balance'))
                                @livewire('Layouts.ReportsPayables')
                            @endif
                            {{-- not now --}}
                            {{-- @if (Auth::user()->can('report.inventory.validation-summary'))
                                @livewire('Layouts.ReportsInventory')
                            @endif --}}

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
                        Auth::user()->can('price-location') ||
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
                        <ul class="nav nav-treeview bg-blue-dark">
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
                                    Auth::user()->can('price-level.view') ||
                                    Auth::user()->can('price-location'))
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
