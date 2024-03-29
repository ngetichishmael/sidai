<div class="row">
   <div class="col-md-12">
      <ul class="nav nav-tabs" style="font-weight: bolder; font-size:12px;">
         {{-- <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('preorders.reports') ? 'active' : '' }}" href="{{ route('preorders.reports') }}">Preorders</a>
         </li> --}}
         
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('delivery.reports') ? 'active' : '' }}" href="{{ route('delivery.reports') }}">Orders</a>
         </li>
         {{-- <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
             <a class="nav-link {{ request()->routeIs('supplier.reports') ? 'active' : '' }}" href="{{ route('supplier.reports') }}">Suppliers</a>
         </li> --}}
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('distributor.reports') ? 'active' : '' }}" href="{{ route('distributor.reports') }}">Distributors</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('warehouse.reports') ? 'active' : '' }}" href="{{ route('warehouse.reports') }}">Warehouse</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('regional.reports') ? 'active' : '' }}" href="{{ route('regional.reports') }}">Regional</a>
         </li>
         {{-- <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('visitation.reports') ? 'active' : '' }}" href="{{ route('visitation.reports') }}">Visitation</a>
         </li> --}}
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('target.reports') ? 'active' : '' }}" href="{{ route('target.reports') }}">Targets</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('sidai.reports') ? 'active' : '' }}" href="{{ route('sidai.reports') }}">Users</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('clients.reports') ? 'active' : '' }}" href="{{ route('clients.reports') }}">Customers</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('payments.reports') ? 'active' : '' }}" href="{{ route('payments.reports') }}">Payments</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('inventory.reports') ? 'active' : '' }}" href="{{ route('inventory.reports') }}">Inventory</a>
         </li>
         <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Click to View Reports">
            <a class="nav-link {{ request()->routeIs('employee.reports') ? 'active' : '' }}" href="{{ route('employee.reports') }}">Employees</a>
         </li>
      </ul>
   </div>
</div>

<style>
  nav.nav-tabs .nav-item .nav-link {
      border-bottom: 2px solid rgb(255, 128, 85);
   }
</style>
<style>
   /* Define a custom tooltip class with the desired background color */
   .custom-tooltip {
      background-color: rgba(252, 95, 95, 0.62); /* Change this color to your desired background color */
      color: #fc9d7d; /* Text color for the tooltip */
   }
</style>

<script>
   $(document).ready(function () {
      // Initialize tooltips with the custom class
      $('[data-toggle="tooltip"]').tooltip({
         template: '<div class="tooltip custom-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
      });
   });
</script>
