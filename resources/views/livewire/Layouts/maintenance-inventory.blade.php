     <li id="inventory" class="nav-item {{ request()->is('maintenance/inventory*') ? 'menu-open' : '' }}">
         <a href="#" class="nav-link {{ request()->is('maintenance/inventory*') ? 'active' : '' }}">
             <i class="fa fa-cubes nav-icon"></i>
             <p> Inventory <i class="right fas fa-angle-left"></i> </p>
         </a>
         <ul class="nav nav-treeview">
             @can('items.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryitem') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/items*') ? 'active' : '' }}">
                         <i class="fa fa-cube nav-icon"></i>
                         <p>Items</p>
                     </a>
                 </li>
             @endcan


             @can('item-group.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryitem_group') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/item-group*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Item Group</p>
                     </a>
                 </li>
             @endcan

             @can('item-class.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryitem_class') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/item-class*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Item Class</p>
                     </a>
                 </li>
             @endcan

             @can('item-sub-class.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryitem_sub_class') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/item-sub-class*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Item Sub Class</p>
                     </a>
                 </li>
             @endcan


             @can('manufacturer.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventorymanufacturers') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/manufacturers*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Manufacturers</p>
                     </a>
                 </li>
             @endcan

             @can('ship-via.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryship_via') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/ship-via*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Ship Via</p>
                     </a>
                 </li>
             @endcan

             @can('price-level.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryprice_level') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/price-level*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Price Level</p>
                     </a>
                 </li>
             @endcan

             @can('unit-of-measure.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryunit_of_measure') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/unit-of-measure*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Unit of Measure</p>
                     </a>
                 </li>
             @endcan

             @can('stock-bin.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventorystock_bin') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/stock-bin*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Stock Bin</p>
                     </a>
                 </li>
             @endcan

             @can('inventory-adjustment-type.view')
                 <li class="nav-item">
                     <a href="{{ route('maintenanceinventoryinventory_adjustment_type') }}"
                         class="nav-link {{ request()->is('maintenance/inventory/inventory-adjustment-type*') ? 'active' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Inventory Adjustment Type</p>
                     </a>
                 </li>
             @endcan

         </ul>
     </li>
