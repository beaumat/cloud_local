<li id="patients" class="nav-item {{ request()->is('reports/patients*') ? 'menu-open' : '' }} ">
    <a href="#" class="nav-link {{ request()->is('reports/patients*') ? 'active font-weight-bold' : '' }}">
        <i class="fa fa-file-text-o  nav-icon"></i>
        <p> Patients <i class="right fas fa-angle-left"></i> </p>
    </a>
    <ul class="nav nav-treeview">
        @if (Auth::user()->can('report.patient.sales'))
            <li class="nav-item ">
                <a href="{{ route('reportspatient_sales_report') }}"
                    class="nav-link {{ request()->is('reports/patients/sales*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Sales</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('report.patient.treatment'))
            <li class="nav-item ">
                <a href="{{ route('reportspatient_treatment_report') }}"
                    class="nav-link {{ request()->is('reports/patients/treatment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Treatment</p>
                </a>
            </li>
        @endif
        @if (Auth::user()->can('report.patient.balance'))
            <li class="nav-item ">
                <a href="{{ route('reportspatient_balance_report') }}"
                    class="nav-link {{ request()->is('reports/patients/balance*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Balance</p>
                </a>
            </li>
        @endif

        @if (Auth::user()->can('report.philhealth.monitoring'))
            <li class="nav-item ">
                <a href="{{ route('reportsphilhealth_monitoring') }}"
                    class="nav-link {{ request()->is('reports/patients/philhealth-monitoring*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Philhealth Monitoring</p>
                </a>
            </li>
        @endif

        @if (Auth::user()->can('report.philhealth.availment'))
            <li class="nav-item ">
                <a href="{{ route('reportsphilhealth_availment_list') }}"
                    class="nav-link {{ request()->is('reports/patients/philhealth-availment-list*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Philhealth Availment</p>
                </a>
            </li>
        @endif
        {{-- @if (Auth::user()->can('report.patient.doctor-pf'))
            <li class="nav-item ">
                <a href="{{ route('reportspatient_doctor_fee_report') }}"
                    class="nav-link {{ request()->is('reports/patients/doctor-pro-fees*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Doctor PF</p>
                </a>
            </li>
        @endif --}}
        <li class="nav-item ">
                <a href="{{ route('reportsguarantee_letter') }}"
                    class="nav-link {{ request()->is('reports/reports/guarantee-letter*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Guarantee Letter</p>
                </a>
            </li>

    </ul>
</li>
