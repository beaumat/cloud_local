
       <li class="nav-item {{ request()->is('vendors*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ request()->is('vendors*') ? 'active ' : '' }}">
               <i class="nav-icon fas fa-user-tie"></i>
               <p> Vendors <i class="right fas fa-angle-left"></i> </p>
           </a>
           <ul class="nav nav-treeview">
               @can('vendor.purchase-order.view')
                   <li class="nav-item">
                       <a href="{{ route('vendorspurchase_order') }}"
                           class="nav-link {{ request()->is('vendors/purchase-order*') ? 'active' : '' }}">
                           <i class="fas fa-shopping-cart nav-icon"></i>
                           <p>Purchase Order</p>
                       </a>
                   </li>
               @endcan
               @can('vendor.bill.view')
                   <li class="nav-item">
                       <a href="{{ route('vendorsbills') }}"
                           class="nav-link {{ request()->is('vendors/bills*') ? 'active' : '' }}">
                           <i class="fas fa-file-invoice nav-icon"></i>
                           <p>Bills</p>
                       </a>
                   </li>
               @endcan
               @can('vendor.bill-payment.view')
                   <li class="nav-item">
                       <a href="{{ route('vendorsbill_payment') }}"
                           class="nav-link {{ request()->is('vendors/bill-payments*') ? 'active' : '' }}">
                           <i class="fas fa-money-bill nav-icon"></i>
                           <p>Pay Bills</p>
                       </a>
                   </li>
               @endcan
               @can('vendor.bill-credit.view')
                   <li class="nav-item">
                       <a href="{{ route('vendorsbill_credit') }}"
                           class="nav-link {{ request()->is('vendors/bill-credits*') ? 'active' : '' }}">
                           <i class="fas fa-credit-card nav-icon"></i>
                           <p>Bill Credits</p>
                       </a>
                   </li>
               @endcan
           </ul>
       </li>
