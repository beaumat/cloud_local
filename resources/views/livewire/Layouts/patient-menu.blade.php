<li id="patients" class="nav-item {{ request()->is('patients*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('patients*') ? 'active ' : '' }}"> <i
            class="nav-icon fas fa-wheelchair "></i>
        <p> Patients <i class="fas fa-angle-left right"></i> </p>
    </a>

    <ul class="nav nav-treeview bg-blue-dark">
        @can('patient.schedule.view')
            <li class="nav-item">
                <a href="{{ route('patientsschedules') }}"
                    class="nav-link {{ request()->is('patients/schedules*') ? 'text-warning font-weight-bold' : '' }}"> <i
                        class="fas fa-calendar nav-icon"></i>
                    <p>Schedules</p>
                </a>
            </li>
        @endcan
        @can('patient.service-charges.view')
            <li class="nav-item"> <a href="{{ route('patientsservice_charges') }}"
                    class="nav-link {{ request()->is('patients/service-charges*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-file-invoice nav-icon"></i>
                    <p>Service Charges</p>
                </a> </li>
        @endcan
        @can('patient.payment.view')
            <li class="nav-item">
                <a href="{{ route('patientspayment') }}"
                    class="nav-link {{ request()->is('patients/payments*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-money-bill-wave nav-icon"></i>
                    <p>Cash/GL Payments</p>
                </a>
            </li>
        @endcan
        @can('patient.treatment.view')
            <li class="nav-item">
                <a href="{{ route('patientshemo') }}"
                    class="nav-link {{ request()->is('patients/hemodialysis-treatment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-medkit nav-icon"></i>
                    <p>Treatment</p>
                </a>
            </li>
        @endcan



        @can('patient.philhealth.view')
            <li id="patients" class="nav-item {{ request()->is('patients/phil-health*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('patients/phil-health*') ? 'active' : '' }}"> <i
                        class="nav-icon fas fa-wheelchair "></i>
                    <p> Philhealth <i class="fas fa-angle-left right"></i> </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item {{ request()->is('patients/phil-health/forms*') ? 'menu-open' : '' }}">
                        <a href="{{ route('patientsphic') }}"
                            class="nav-link {{ request()->is('patients/phil-health/forms*') ? 'text-warning font-weight-bold' : '' }}">
                            <i class="fas fa-hospital-o nav-icon"></i>
                            <p>Soa/Forms</p>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('patients/phil-health/payments*') ? 'menu-open' : '' }}">
                        <a href="{{ route('patientsphic_pay') }}"
                            class="nav-link {{ request()->is('patients/phil-health/payments*') ? 'text-warning font-weight-bold' : '' }}">
                            <i class="fa fa-get-pocket nav-icon"></i>
                            <p>Payment Notes</p>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        {{-- <li class="nav-item">
                            <a href="{{ route('patientssoa') }}"
                                class="nav-link {{ request()->is('patients/statement-of-account*') ? 'active' : '' }}">
                                <i class="fas fa-newspaper nav-icon"></i>
                                <p>GL Statement</p>
                            </a>
                        </li> --}}
    </ul>
</li>
