<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header mb-3 mt-0">
        <ul class="nav navbar-nav flex-row ">
            <li class="nav-item me-auto">
                <a class="" href="#">
                    <center><img src="{!! asset('app-assets/images/sidaiweblogo.png') !!}" alt="Sidai Africa Limited" class="img" width="100%">
                    </center>
                </a>
            </li>
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
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM'))
                <li class="nav-item {!! Nav::isRoute('customer') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Customers Managements</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('customer') }}">
                                <span class="menu-item text-truncate">Customers</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('creditors') }}">
                                <span class="menu-item text-truncate">Creditors</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('approveCreditors') }}">
                                <span class="menu-item text-truncate">Approve Creditors</span></a>
                        </li>

                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('groupings') }}">
                                <span class="menu-item text-truncate">Customer Groups</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('CustomerComment') }}"><span
                                    class="menu-item text-truncate">Comments</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM') ||
                    (Auth::check() && Auth::user()->account_type == 'Shop-Attendee'))
                <li class="nav-item {!! Nav::isResource('orders') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather='shopping-cart'></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Orders</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{{ route('orders.pendingorders') }}">
                                <span class="menu-item text-truncate">Pending Orders</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{{ route('orders.pendingdeliveries') }}">
                                <span class="menu-item text-truncate">Pending Deliveries</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('delivery.index') !!}">
                                <span class="menu-title text-truncate" data-i18n="Todo">
                                    Delivery History</span>
                            </a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('orders.distributororders') !!}">
                                <span class="menu-title text-truncate" data-i18n="Todo">
                                    Distributor Orders</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {!! Nav::isRoute('payment') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="credit-card"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Payment Management</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('PaidPayment') }}"><span
                                    class="menu-item text-truncate">Payments</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer*') !!}"
                                href="{{ route('PendingPayment') }}"><span class="menu-item text-truncate">Creditors
                                    Payment</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {!! Nav::isResource('warehousing') !!}">
                    <a class="d-flex align-items-center" href="#"><i data-feather='archive'></i><span
                            class="menu-title text-truncate" data-i18n="Invoice"> Warehousing Management</span></a>
                    <ul class="menu-content">
                        @if (Auth::check() && Auth::user()->account_type == 'NSM')
                            <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                    href="{!! route('warehousing.index') !!}">
                                    <span class="menu-item text-truncate">All
                                        Warehouses</span></a></li>
                        @endif
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('inventory.approval') !!}"><span class="menu-item text-truncate">Approve
                                    Stock</span></a></li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('supplier') !!}">
                                <span class="menu-item text-truncate">Suppliers</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('pricing') }}"><span class="menu-item text-truncate">Pricing</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('product.category') !!}">
                                <span class="menu-item text-truncate">Categories</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('product.brand') !!}">
                                <span class="menu-item text-truncate">Brands</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if ((Auth::check() && Auth::user()->account_type == 'Admin') || (Auth::check() && Auth::user()->account_type == 'NSM'))
                <li class="nav-item {!! Nav::isResource('users') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Users</span>
                    </a>
                    <ul class="menu-content">
                        @if (Auth::check() && Auth::user()->account_type == 'Admin')
                            <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                    href="{!! route('users.nsm') !!}">
                                    <span class="menu-item text-truncate">NSM</span></a>
                            </li>
                        @endif
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('rsm') !!}">
                                <span class="menu-item text-truncate">RSM</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('tsr') !!}">
                                <span class="menu-item text-truncate">TSR</span></a>
                        </li>

                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('shop-attendee') !!}">
                                <span class="menu-item text-truncate">Shop Attendee</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('td') !!}">
                                <span class="menu-item text-truncate">TD</span></a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item {!! Nav::isResource('target') !!}">
                    <a class="d-flex align-items-center" href="#"><i data-feather="target"></i><span
                            class="menu-title text-truncate" data-i18n="Invoice">Target</span></a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href=" {{ route('sales.target') }}">
                                <span class="menu-item text-truncate">Sales</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{{ route('visit.target') }}">
                                <span class="menu-item text-truncate">Visits</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{{ route('leads.target') }}">
                                <span class="menu-item text-truncate">Leads</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{{ route('order.target') }}">
                                <span class="menu-item text-truncate">Orders</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM') ||
                    (Auth::check() && Auth::user()->account_type == 'Shop-Attendee'))
                <li class="nav-item {{ Nav::isResource('users.reports') }}">
                    <a class="d-flex align-items-center" href="{{ route('users.reports') }}"><i
                            data-feather='file-text'></i><span class="menu-title text-truncate" data-i18n="Invoice">
                            All Reports</span></a>
                </li>
            @endif
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM'))
                <li class="nav-item {!! Nav::isResource('regions') !!}">
                    <a class="d-flex align-items-center" href="#"><i data-feather="map-pin"></i><span
                            class="menu-title text-truncate" data-i18n="Invoice">Regions</span></a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px">
                            <a class="d-flex align-items-center nav-item {!! Nav::isResource('regions') !!}"
                                href="{{ route('regions') }}"><span
                                    class="menu-item text-truncate">Regions</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isResource('subregions') !!}"
                                href="{{ route('subregions') }}"><span class="menu-item text-truncate">Sub
                                    Regions</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center{!! Nav::isResource('areas') !!}"
                                href="{{ route('areas') }}">
                                <span class="menu-item text-truncate">Routes</span></a>
                        </li>

                        <li class="nav-item {!! Nav::isResource('maps') !!}">
                            <a class="d-flex align-items-center" href="#"><span
                                    class="menu-title text-truncate" data-i18n="Invoice">Maps</span></a>
                            <ul class="menu-content">
                                <li class="nav-item {!! Nav::isResource('maps') !!}">
                                    <a class="d-flex align-items-center" href="{!! route('maps') !!}">
                                        <span class="menu-title text-truncate" data-i18n="Todo">
                                            Maps</span>
                                    </a>
                                </li>
                                <li class="nav-item {!! Nav::isResource('current-information') !!}">
                                    <a class="d-flex align-items-center" href="{!! route('current-information') !!}">
                                        <span class="menu-title text-truncate" data-i18n="Todo">
                                            Sales Agents</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {!! Nav::isResource('routes') !!}">
                    <a class="d-flex align-items-center" href="">
                        <i data-feather='map'></i><span class="menu-title text-truncate" data-i18n="Todo"> Route
                            Scheduling</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href=" {!! route('routes.index') !!}">
                                <span class="menu-item text-truncate">Assigned</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                href="{!! route('routes.individual') !!}">
                                <span class="menu-item text-truncate">Individual</span></a>
                        </li>
                    </ul>
                </li>
            @endif
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM') ||
                    (Auth::check() && Auth::user()->account_type == 'Shop-Attendee'))
                <li class="nav-item {!! Nav::isResource('notification') !!}">
                    <a class="d-flex align-items-center" href=""><i data-feather="message-circle"></i><span
                            class="menu-title text-truncate" data-i18n="Invoice">Chats</span></a>
                </li>
            @endif
            @if (
                (Auth::check() && Auth::user()->account_type == 'Admin') ||
                    (Auth::check() && Auth::user()->account_type == 'NSM') ||
                    (Auth::check() && Auth::user()->account_type == 'RSM'))
                <li class="nav-item {!! Nav::isResource('survey') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="clipboard"></i><span class="menu-title text-truncate">Survey</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px">
                            <a class="d-flex align-items-center" href="{!! route('survey.index') !!}">

                                <span class="menu-item text-truncate">Survey</span>
                            </a>
                        </li>
                        <li style="padding-left: 50px">
                            <a class="d-flex align-items-center {!! Nav::isResource('survey') !!}"
                                href="{!! route('SurveryResponses') !!}">

                                <span class="menu-item text-truncate">Responses</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {!! Nav::isResource('activity') !!}">
                    <a class="d-flex align-items-center" href="{!! route('activity.index') !!}">
                        <i data-feather='activity'></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Activity Logs </span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
