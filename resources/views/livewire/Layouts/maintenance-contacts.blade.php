<li id="contacts" class="nav-item {{ request()->is('maintenance/contact*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('maintenance/contact*') ? 'active' : '' }}">
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