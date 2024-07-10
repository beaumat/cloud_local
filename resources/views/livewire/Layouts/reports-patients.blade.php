      <li id="patients" class="nav-item {{ request()->is('reports/patients*') ? 'menu-open' : '' }} ">
          <a href="#" class="nav-link {{ request()->is('reports/patients*') ? 'active font-weight-bold' : '' }}">
              <i class="fa fa-file-text-o  nav-icon"></i>
              <p>
                  Patients
                  <i class="right fas fa-angle-left"></i>
              </p>
          </a>
          <ul class="nav nav-treeview">
              <li class="nav-item ">
                  <a href="{{ route('reportspatient_sales_report') }}" class="nav-link {{ request()->is('reports/patients/sales*') ? 'active' : '' }}">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Sales</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Philheath Forms (Temp)</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="fa fa-print nav-icon"></i>
                      <p>Doctors PF</p>
                  </a>
              </li>
          </ul>
      </li>
