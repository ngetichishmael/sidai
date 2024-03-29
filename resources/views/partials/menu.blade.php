<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow mb-5" data-scroll-to-active="true">

    <div class="navbar-header mb-3 mt-0 text-center">
        <ul class="nav navbar-nav flex-row ">
            <li class="nav-item me-auto">
                <a class="" href="#">
                    <center><img src="{!! asset('app-assets/images/sidaiweblogo.png') !!}" alt="Sidai Africa Limited" class="img" width="100%">
                    </center>
                </a>
            </li>
        </ul>

    </div>
    <div class="shadow-bottom "></div>
    <div class="main-menu-content mb-3">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {!! Nav::isRoute('app.dashboard') !!}">
                <a class="d-flex align-items-center" href="{!! route('app.dashboard') !!}">
                    <i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Todo">
                        Dashboards</span>
                </a>
            </li>
{{--           @dd(\Illuminate\Support\Facades\Auth::user())--}}
           @haspermissionto(['manager_dashboard', 'admin_dashboard', 'shop_attendee_dashboard'])
                <li class="nav-item {!! Nav::isRoute('customer') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Customers Management</span>
                    </a>
                    <ul class="menu-content">
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('customer') }}">
                                <span class="menu-item text-truncate">Customers</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('creditor') }}">
                                <span class="menu-item text-truncate">Creditors</span></a>
                        </li>

                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('approvecustomers') }}">
                                <span class="menu-item text-truncate">Approve Customers</span></a>
                        </li>
                       @haspermissionto(['manager_dashboard', 'admin_dashboard'])
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                href="{{ route('approveCreditors') }}">
                                <span class="menu-item text-truncate">Approve Creditors</span></a>
                        </li>
                       @endhaspermissionto
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
           @endhaspermissionto
           @haspermissionto(['manager_dashboard', 'admin_dashboard','shop_attendee_dashboard'])
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
                                href="{{ route('orders.vansaleorders') }}">
                                <span class="menu-item text-truncate">Vansale Orders</span></a>
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
                    <a class="d-flex align-items-center {!! Nav::isRoute('warehousing.*') !!}" href="#"><i data-feather='archive'></i><span
                            class="menu-title text-truncate" data-i18n="Invoice"> Warehousing Management</span></a>
                    <ul class="menu-content">
                       @haspermissionto(['manager_dashboard', 'admin_dashboard'])
                       <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('warehousing.index') !!}"
                                                         href="{!! route('warehousing.index') !!}">
                                    <span class="menu-item text-truncate">
                                        Warehouses</span></a></li>
                       @endhaspermissionto
                       @haspermissionto(['shop_attendee_dashboard'])
                       <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('warehousing.index') !!}"
                                                         href="{!! route('warehousing.index') !!}">
                                    <span class="menu-item text-truncate">
                                        Products</span></a></li>
                       @endhaspermissionto
                       @haspermissionto(['manager_dashboard', 'admin_dashboard', 'shop_attendee_dashboard' ])

                       <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                                         href="{!! route('inventory.warehouses') !!}"><span class="menu-item text-truncate">Approve
                                    Stock</span></a></li>
                       <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                                         href="{!! route('supplier') !!}">
                             <span class="menu-item text-truncate">Distributors</span></a>
                       </li>
                       <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isRoute('customer.*') !!}"
                                                         href="{{ route('pricing') }}"><span class="menu-item text-truncate">Pricing</span></a>
                       </li>
                       @endhaspermissionto
                        {{--               <li><a class="d-flex align-items-center" href="{!! route('product.index') !!}"><i --}}
                        {{--                                data-feather="package"></i><span class="menu-item text-truncate">Inventory</span></a></li> --}}

                       <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                                         href="{!! route('product.category') !!}">
                             <span class="menu-item text-truncate">Categories</span></a>
                       </li>
                       <li style="padding-left: 50px"><a class="d-flex align-items-center"
                                                         href="{!! route('product.brand') !!}">
                             <span class="menu-item text-truncate">Brands</span></a>
                       </li>
{{--                       @haspermissionto(['manager_dashboard', 'admin_dashboard'])--}}

{{--                       @endhaspermissionto--}}
                    </ul>
                </li>
           @endhaspermissionto
           @haspermissionto(['manager_dashboard', 'admin_dashboard'])
           @if(Auth::user()->account_type=='Admin' || Auth::user()->account_type=='NSM')
                <li class="nav-item {!! Nav::isResource('users') !!}">
                    <a class="d-flex align-items-center" href="{{route('users.list')}}">
                        <i data-feather="users"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Users</span>
                    </a>
                   @endif
{{--                    <ul class="menu-content">--}}
{{--                        @if (Auth::check() && Auth::user()->account_type == 'Admin')--}}
{{--                            <li style="padding-left: 50px"><a class="d-flex align-items-center"--}}
{{--                                    href="{!! route('users.nsm') !!}">--}}
{{--                                    <span class="menu-item text-truncate">NSM</span></a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                        <li style="padding-left: 50px"><a class="d-flex align-items-center"--}}
{{--                                href="{!! route('rsm') !!}">--}}
{{--                                <span class="menu-item text-truncate">RSM</span></a>--}}
{{--                        </li>--}}
{{--                        <li style="padding-left: 50px"><a class="d-flex align-items-center"--}}
{{--                                href="{!! route('tsr') !!}">--}}
{{--                                <span class="menu-item text-truncate">TSR</span></a>--}}
{{--                        </li>--}}
{{--                        <li style="padding-left: 50px"><a class="d-flex align-items-center"--}}
{{--                                href="{!! route('shop-attendee') !!}">--}}
{{--                                <span class="menu-item text-truncate">Shop Attendee</span></a>--}}
{{--                        </li>--}}
{{--                        <li style="padding-left: 50px"><a class="d-flex align-items-center"--}}
{{--                                href="{!! route('td') !!}">--}}
{{--                                <span class="menu-item text-truncate">TD</span></a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}

                </li>
                @haspermissionto(['manager_dashboard', 'admin_dashboard'])
           <li class="nav-item {!! Nav::isResource('visits') !!}">
            <a class="d-flex align-items-center" href="#"><i data-feather='truck'></i><span
                    class="menu-title text-truncate" data-i18n="Invoice">Visits</span></a>
            <ul class="menu-content">
                <li><a class="nav-item {!! Nav::isResource('UsersVisits') !!} d-flex align-items-center"
                        href="{!! route('UsersVisits') !!}"><i data-feather="user" style="color:#ffffff;"></i><span
                            class="menu-item text-truncate">Users</span></a>
                </li>
                <li><a class="d-flex align-items-center" href="{!! route('CustomerVisits') !!}"><i data-feather="users"
                            style="color:#ffffff;"></i><span class="menu-item text-truncate">Customers</span></a>
                </li>
            </ul>
        </li>
        @endhaspermissionto
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
           @endhaspermissionto
                <li class="nav-item {!! Nav::isResource('regions') !!}">
                    <a class="d-flex align-items-center" href="#"><i data-feather="map-pin"></i><span
                            class="menu-title text-truncate" data-i18n="Invoice">Regions</span></a>
                    <ul class="menu-content">
                       @hasdataaccessto(['all','regional'])
                       @if(Auth::user()->account_type=='Admin' || Auth::user()->account_type=='NSM')
                        <li style="padding-left: 50px">
                            <a class="d-flex align-items-center nav-item {!! Nav::isResource('regions') !!}"
                                href="{{ route('regions') }}"><span
                                    class="menu-item text-truncate">Regions</span></a>
                        </li>
                       @endif
                       @endhasdataaccessto
                        <li style="padding-left: 50px"><a class="d-flex align-items-center {!! Nav::isResource('subregions') !!}"
                                href="{{ route('subregions') }}"><span class="menu-item text-truncate">Sub Regions</span></a>
                        </li>
                        <li style="padding-left: 50px"><a class="d-flex align-items-center{!! Nav::isResource('areas') !!}"
                                href="{{ route('areas') }}">
                                <span class="menu-item text-truncate">Routes</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {!! Nav::isResource('maps') !!}">
                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="map"></i><span class="menu-title text-truncate" data-i18n="Todo">
                            Maps</span></a>
                            <ul class="menu-content">
                                <li style="padding-left: 50px"><a class="d-flex align-items-center" href="{!! route('maps') !!}">
                                <span class="menu-item text-truncate">Maps</span></a>
                                </li>
                                <li style="padding-left: 50px"><a class="d-flex align-items-center" href="{!! route('current-information') !!}">
                                <span class="menu-item text-truncate">Sales Agents</span></a>
                                </li>
                            </ul>
                </li>

<li class="nav-item {!! Nav::isResource('routes') !!}">
<a class="d-flex align-items-center" href="">
<i data-feather='compass'></i><span class="menu-title text-truncate" data-i18n="Todo">Schedules</span>
</a>
    <ul class="menu-content">
    <li style="padding-left: 50px"><a class="d-flex align-items-center" href=" {!! route('routes.index') !!}">
    <span class="menu-item text-truncate">Assigned</span></a>
    </li>
    <li style="padding-left: 50px"><a class="d-flex align-items-center" href="{!! route('routes.individual') !!}">
    <span class="menu-item text-truncate">Individual</span></a>
    </li>
    </ul>
</li>

<li class="nav-item {!! Nav::isResource('stocklift') !!}">
    <a class="d-flex align-items-center" href="{!! route('stock.lifts') !!}">
       <i data-feather="tag"></i>
       <span class="menu-title text-truncate" data-i18n="Invo">Stock Lifts</span>
    </a>
 </li>
<li class="nav-item {!! Nav::isResource('reconcilition') !!}">
    <a class="d-flex align-items-center" href="{{ route('stock.recon') }}">
       <i data-feather="book"></i>
       <span class="menu-title text-truncate" data-i18n="voice">Stock Reconciliation</span>
    </a>
 </li>

           @haspermissionto(['manager_dashboard', 'admin_dashboard','shop_attendee_dashboard'])
{{--              <li class="nav-item {!! request()->routeIs('chats.index') ? 'active' : '' !!}">--}}
{{--                 <a class="d-flex align-items-center" href="{{ route('chats.index') }}">--}}
{{--              <li class="nav-item {!! Nav::isResource('support') !!}">--}}
{{--                 <a class="d-flex align-items-center" href="{!! route('support.index') !!}">--}}
{{--                    <i data-feather="message-circle"></i>--}}
{{--                    <span class="menu-title text-truncate" data-i18n="Invoice">Chats</span>--}}
{{--                 </a>--}}
{{--              </li>--}}
{{--           <li class="nav-item {!! Nav::isResource('chat') !!}">--}}
{{--                 <a class="d-flex align-items-center" href="{{url('chats')}}" >--}}
{{--                    <i data-feather="message-circle"></i>--}}
{{--                    <span class="menu-title text-truncate" data-i18n="Invoice">Chats</span>--}}
{{--                 </a>--}}
{{--              </li>--}}
           @endhaspermissionto
           @haspermissionto(['manager_dashboard', 'admin_dashboard'])
              @if(Auth::user()->account_type=='Admin' || Auth::user()->account_type=='NSM')
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
   <a class="d-flex align-items-center {!! Nav::isResource('survey') !!}" href="{!! route('SurveryResponses') !!}">
      <span class="menu-item text-truncate">Responses</span>
   </a>
</li>
</ul>
</li>
              @endif
           @endhaspermissionto
           @haspermissionto(['manager_dashboard', 'admin_dashboard','shop_attendee_dashboard'])
              @if(Auth::user()->account_type=='Admin' || Auth::user()->account_type=='NSM')
    <li class="nav-item {!! Nav::isResource('reports') !!}">
       <a class="d-flex align-items-center" href="#">
          <i data-feather="layers"></i><span class="menu-title text-truncate">Reports</span>
       </a>
       <ul class="menu-content">
          <li style="padding-left: 50px">
        <a class="d-flex align-items-center" href="{!! route('users.reports') !!}"><i
                data-feather='file-text'></i><span class="menu-title text-truncate" data-i18n="Invoice">
                All Reports</span></a>
          </li>
            {{-- TODO : adding daily reports--}}
          <li style="padding-left: 50px">
          <a class="d-flex align-items-center" href="{!! route('users.dailyreports') !!}"><i
                data-feather='repeat'></i><span class="menu-title text-truncate" data-i18n="Invoice">
                Daily Reports</span></a>
          </li>
       </ul>
    </li>
              @endif
              @endhaspermissionto
           @haspermissionto(['manager_dashboard', 'admin_dashboard'])
           <li class="nav-item {!! Nav::isResource('activity')!!} mb-5">
<a class="d-flex align-items-center" href="{!! route('activity.index') !!}">
<i data-feather='activity'></i><span class="menu-title text-truncate" data-i18n="Todo"> Activity Logs </span>
</a>
</li>
              @endhaspermissionto
</ul>
</div>
   <div class="md-5">
   </div>
</div>
