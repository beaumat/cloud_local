      <li id="settings" class="nav-item {{ request()->is('maintenance/settings*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('maintenance/settings*') ? 'active' : '' }}">
              <i class="fa fa-wrench nav-icon"></i>
              <p> Settings <i class="right fas fa-angle-left"></i> </p>
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

              @can('roles-and-permission')
                  <li class="nav-item">
                      <a href="{{ route('maintenancesettingsroles') }}"
                          class="nav-link {{ request()->is('maintenance/settings/rolespermission*') ? 'active' : '' }}">
                          <i class="fa fa-file nav-icon"></i>
                          <p>Roles & Permission</p>
                      </a>
                  </li>
              @endcan


              @can('location.view')
                  <li class="nav-item">
                      <a href="{{ route('maintenancesettingslocation') }}"
                          class="nav-link {{ request()->is('maintenance/settings/location*') ? 'active' : '' }}">
                          <i class="fa fa-file nav-icon"></i>
                          <p>Location</p>
                      </a>
                  </li>
              @endcan

              @can('location-group.view')
                  <li class="nav-item">
                      <a href="{{ route('maintenancesettingslocation_group') }}"
                          class="nav-link {{ request()->is('maintenance/settings/location-group*') ? 'active' : '' }}">
                          <i class="fa fa-file nav-icon"></i>
                          <p>Location Group</p>
                      </a>
                  </li>
              @endcan

              @can('option')
                  <li class="nav-item">
                      <a href="{{ route('maintenancesettingsoption') }}"
                          class="nav-link {{ request()->is('maintenance/settings/option*') ? 'active' : '' }}">
                          <i class="fa fa-file nav-icon"></i>
                          <p>Options</p>
                      </a>
                  </li>
              @endcan

          </ul>
      </li>
