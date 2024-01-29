<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header mb-1">
       <ul class="nav navbar-nav flex-row">
          <li class="nav-item me-auto">
             <a class="" href="#">
                <center><img src="{!! asset('app-assets/images/ssealogo.jpg') !!}" alt="sokoflow" class="img" width="80%"></center>
             </a>
          </li>
          {{-- <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li> --}}
       </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {!! Nav::isRoute('app.dashboard') !!}">
                <a class="d-flex align-items-center" href="{!! route('app.dashboard') !!}">
                    <i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Todo">
                        Dashboards</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('product') !!}">
                <a class="d-flex align-items-center" href="#"><i data-feather="list"></i><span
                        class="menu-title text-truncate" data-i18n="Invoice">Products</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{!! route('product.index') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Products</span></a></li>
                    <li><a class="d-flex align-items-center" href="{!! route('product.category') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Categories</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{!! route('product.brand') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Brands</span></a></li>
                </ul>
            </li>
            <li class="nav-item {!! Nav::isResource('inventory') !!}">
                <a class="d-flex align-items-center" href="#"><i data-feather='package'></i><span
                        class="menu-title text-truncate" data-i18n="Invoice"> Inventory</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{!! route('inventory.allocated') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Allocated
                                Stock</span></a></li>
                </ul>
            </li>
            <li class="nav-item {!! Nav::isRoute('customer.index') !!}">
                <a class="d-flex align-items-center" href="{!! route('customer.index') !!}">
                    <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo">
                        Customers</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('checkin') !!}">
                <a class="d-flex align-items-center {!! Nav::isRoute('customer.checkin.index') !!}" href="{!! route('customer.checkin.index') !!}">
                    <i data-feather='log-in'></i><span class="menu-title text-truncate" data-i18n="Todo">Customer Visits
                    </span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('supplier') !!}">
                <a class="d-flex align-items-center" href="#"><i data-feather='refresh-ccw'></i><span
                        class="menu-title text-truncate" data-i18n="Invoice">Suppliers</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{!! route('supplier.index') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Suppliers</span></a>
                    </li>
                    <li><a class="d-flex align-items-center" href="{!! route('supplier.category.index') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Categories</span></a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {!! Nav::isResource('target') !!}">
               <a class="d-flex align-items-center" href="#"><i data-feather="target"></i><span
                       class="menu-title text-truncate" data-i18n="Invoice">Target</span></a>
               <ul class="menu-content">
                   <li><a class="d-flex align-items-center" href=" {{ route('sales.target') }}"><i
                               data-feather="credit-card"></i><span class="menu-item text-truncate">Sales</span></a></li>
                   <li><a class="d-flex align-items-center" href="{{ route('visit.target') }}"><i
                               data-feather="truck"></i><span class="menu-item text-truncate">Visits</span></a>
                   </li>
                   <li><a class="d-flex align-items-center" href="{{ route('leads.target') }}"><i
                               data-feather="compass"></i><span class="menu-item text-truncate">Leads</span></a>
                   </li>
                   <li><a class="d-flex align-items-center" href="{{ route('order.target') }}"><i
                               data-feather="shopping-cart"></i><span class="menu-item text-truncate">Orders</span></a>
                   </li>
               </ul>
           </li>
            <li class="nav-item {!! Nav::isResource('orders') !!}">
                <a class="d-flex align-items-center" href="{!! route('orders.index') !!}">
                    <i data-feather='shopping-cart'></i><span class="menu-title text-truncate" data-i18n="Todo">
                        Orders</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('deliver') !!}">
                <a class="d-flex align-items-center" href="{!! route('delivery.index') !!}">
                    <i data-feather='truck'></i>
                   <span class="menu-title text-truncate" data-i18n="Todo">Delivery History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='calendar'></i><span class="menu-title text-truncate" data-i18n="Todo"> Scheduled
                        visits</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('survey') !!}">
                <a class="d-flex align-items-center" href="#">
                    <i data-feather='clipboard'></i><span class="menu-title text-truncate">Survey</span>
                </a>
                <ul class="menu-content">
                    <li>
                        <a class="d-flex align-items-center" href="{!! route('survey.index') !!}">
                            <i data-feather="circle">
                            </i>
                            <span class="menu-item text-truncate">Survey</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {!! Nav::isResource('routes') !!}">
                <a class="d-flex align-items-center" href="{!! route('routes.index') !!}">
                    <i data-feather='map'></i><span class="menu-title text-truncate" data-i18n="Todo"> Route
                        Scheduling</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('warehousing') !!}">
                <a class="d-flex align-items-center" href="#"><i data-feather='archive'></i><span
                        class="menu-title text-truncate" data-i18n="Invoice"> Warehousing</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{!! route('warehousing.index') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">All
                                Warehouses</span></a></li>
                    <li><a class="d-flex align-items-center" href="{!! route('warehousing.create') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Add
                                Warehouse</span></a></li>
                </ul>
            </li>
            <li class="nav-item {!! Nav::isResource('users') !!}">
                <a class="d-flex align-items-center" href="{!! route('users.index') !!}">
                    <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo"> Users</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('territories') !!}">
                <a class="d-flex align-items-center" href="{!! route('territories.index') !!}">
                    <i data-feather='map-pin'></i><span class="menu-title text-truncate" data-i18n="Todo">
                        Territories</span>
                </a>
            </li>
            <li class="nav-item {!! Nav::isResource('settings') !!}">
                <a class="d-flex align-items-center" href="#"><i data-feather='settings'></i><span
                        class="menu-title text-truncate" data-i18n="Invoice"> Settings</span></a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{!! route('settings.account') !!}"><i
                                data-feather="circle"></i><span class="menu-item text-truncate">Account</span></a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
 </div>


