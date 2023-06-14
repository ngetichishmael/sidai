<div class="mt-5 pt-5">
<section class="app-user-list" id="vansalesSection">
    <div class="card">
        <h5 class="card-header">Total Vansales</h5>
        <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="selectSmall">Select Per Page</label>
                    <select wire:model='perVansale' class="form-control form-control-sm" id="selectSmall">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="pt-0 card-datatable table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Sales Associates</th>
                        <th>Balance </th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vansalesTotal as $key=>$sale)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sale->order_code }}</td>
                            <td>{{ $sale->user()->pluck('name')->implode('') }}</td>
                            <td>{{ $sale->customer()->pluck('customer_name')->implode('') }}</td>
                            <td>{{ $sale->balance }}</td>
                            <td>{{ $sale->payment_status }}</td>
                            <td>{{ $sale->updated_at }}</td>
                        </tr>
                    @empty
                        <x-emptyrow>
                            6
                        </x-emptyrow>
                    @endforelse
                </tbody>
            </table>
            {{ $vansalesTotal->links() }}
        </div>
    </div>
</section>
<section class="app-user-list" id="preorderSection">
    <div class="card">
        <h5 class="card-header">Pre Order</h5>
        <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="selectSmall">Select Per Page</label>
                    <select wire:model='perPreorder' class="form-control form-control-sm" id="selectSmall">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="pt-0 card-datatable table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Sales Associates</th>
                        <th>Balance </th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($preorderTotal as $key=>$sale)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sale->order_code }}</td>
                            <td>{{ $sale->user()->pluck('name')->implode('') }}</td>
                            <td>{{ $sale->customer()->pluck('customer_name')->implode('') }}</td>
                            <td>{{ $sale->balance }}</td>
                            <td>{{ $sale->payment_status }}</td>
                            <td>{{ $sale->updated_at }}</td>
                        </tr>
                    @empty
                        <x-emptyrow>
                            6
                        </x-emptyrow>
                    @endforelse
                </tbody>
            </table>
            {{ $preorderTotal->links() }}
        </div>
    </div>
</section>
<section class="app-user-list" id="buyingCustomersSection">
   <div class="card">
      <h5 class="card-header">Buying Customers</h5>
      <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
         <div class="col-md-2">
            <div class="form-group">
               <label for="selectSmall">Select Per Page</label>
               <select wire:model='perBuyingCustomer' class="form-control form-control-sm" id="selectSmall">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="1000">1000</option>
               </select>
            </div>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="pt-0 card-datatable table-responsive">
         <table class="table">
            <thead class="thead-light">
            <tr>
               <th>ID</th>
               {{--                        <th>Order Code</th>--}}
               <th>Customer</th>
               <th>Type</th>
               {{--                        <th>Group</th>--}}
               {{--                        <th>Sales Associates</th>--}}
               <th>Region</th>
               {{--                        <th>Payment Status</th>--}}
               <th>Route</th>
               <th>Registered By</th>
               <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($customersCountTotal as $key=>$sale)
               <tr>
                  <td>{{ $key + 1 }}</td>
                  {{--                            <td>{{ $sale->order_code }}</td>--}}
                  <td>{{ $sale->customer_name ?? '' }}</td>
                  <td>{{ $sale->customer_type ?? '' }}</td>
                  {{--                            <td>{{ $sale->customer_group ?? '' }}</td>--}}
                  {{--                            <td>{{ $sale->customer->customer_name ?? '' }}</td>--}}
                  {{--                            <td>{{ $sale->balance }}</td>--}}
                  {{--                            <td>{{ $sale->payment_status }}</td>--}}
                  <td>{{$sale->Region->name ?? ''}}</td>
                  <td>{{$sale->Area->name ?? ''}}</td>
                  <td>{{$sale->Creator->name ?? ''}}</td>
                  <td>{{ $sale->updated_at }}</td>
               </tr>
            @empty
               <x-emptyrow>
                  6
               </x-emptyrow>
            @endforelse
            </tbody>
         </table>
         {{ $customersCountTotal->links() }}
      </div>
   </div>
</section>
<section class="app-user-list " id="distributorsOrders">
   <div class="card">
      <h5 class="card-header">Distributors Order</h5>
      <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
         <div class="col-md-2">
            <div class="form-group">
               <label for="selectSmall">Select Per Page</label>
               <select wire:model='perOrderFulfilment' class="form-control form-control-sm" id="selectSmall">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="1000">1000</option>
               </select>
            </div>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="pt-0 card-datatable table-responsive">
         <table class="table">
            <thead class="thead-light">
            <tr>
               <th>ID</th>
               <th>Order Code</th>
               <th>Customer</th>
               <th>Distributor</th>
               <th>Balance </th>
               <th>Payment Status</th>
               <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orderfullmentbydistributorspage as $key=>$sale)
               <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $sale->order_code }}</td>
                  <td>{{ $sale->customer()->pluck('customer_name')->implode('') }}</td>
                  <td>{{ $sale->distributor()->pluck('name')->implode('') }}</td>
                  <td>{{ $sale->balance }}</td>
                  <td>{{ $sale->payment_status }}</td>
                  <td>{{ $sale->updated_at }}</td>
               </tr>
            @empty
               <x-emptyrow>
                  6
               </x-emptyrow>
            @endforelse
            </tbody>
         </table>
         {{ $orderfullmentTotal->links() }}
      </div>
   </div>
</section>
<section class="app-user-list" id="orderFulfillmentSection">
    <div class="card">
        <h5 class="card-header">Order Deliveries</h5>
        <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="selectSmall">Select Per Page</label>
                    <select wire:model='perOrderFulfilment' class="form-control form-control-sm" id="selectSmall">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="pt-0 card-datatable table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Sales Associates</th>
                        <th>Balance </th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orderfullmentTotal as $key=>$sale)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sale->order_code }}</td>
                            <td>{{ $sale->user()->pluck('name')->implode('') }}</td>
                            <td>{{ $sale->customer()->pluck('customer_name')->implode('') }}</td>
                            <td>{{ $sale->balance }}</td>
                            <td>{{ $sale->payment_status }}</td>
                            <td>{{ $sale->updated_at }}</td>
                        </tr>
                    @empty
                        <x-emptyrow>
                            6
                        </x-emptyrow>
                    @endforelse
                </tbody>
            </table>
            {{ $orderfullmentTotal->links() }}
        </div>
    </div>
</section>
<section class="app-user-list" id="systemUsers">
    <div class="card">
        <h5 class="card-header">System Users</h5>
        <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="selectSmall">Select Per Page</label>
                    <select wire:model='perOrderFulfilment' class="form-control form-control-sm" id="selectSmall">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="pt-0 card-datatable table-responsive">
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Region</th>
                        <th>Account Type</th>
{{--                        <th>Added By</th>--}}
                        <th>Status</th>
                        <th>Added On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($getUserTotal as $key=>$sale)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $sale->name }}</td>
                            <td>{{ $sale->phone_number ?? ''}}</td>
                            <td>{{ $sale->email ?? ''}}</td>
                            <td>{{ $sale->Region->name ?? ''}}</td>
                            <td>{{ $sale->status }}</td>
                            <td>{{ $sale->created_at }}</td>
                        </tr>
                    @empty
                        <x-emptyrow>
                            6
                        </x-emptyrow>
                    @endforelse
                </tbody>
            </table>
            {{ $orderfullmentTotal->links() }}
        </div>
    </div>
</section>
<section class="app-user-list" id="creditors">
   <div class="card">
      <h5 class="card-header">Overdue Creditors</h5>
      <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
         <div class="col-md-2">
            <div class="form-group">
               <label for="selectSmall">Select Per Page</label>
               <select wire:model='perVansale' class="form-control form-control-sm" id="selectSmall">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="1000">1000</option>
               </select>
            </div>
         </div>
      </div>
   </div>

   <div class="card">
      <div class="pt-0 card-datatable table-responsive">
         <table class="table">
            <thead class="thead-light">
            <tr>
               <th>ID</th>
               <th>Customer Name</th>
               <th>Balance </th>
               <th>Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($vansalesTotal as $key=>$sale)
               <tr>
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $sale->order_code }}</td>
                  <td>{{ $sale->user()->pluck('name')->implode('') }}</td>
                  <td>{{ $sale->customer()->pluck('customer_name')->implode('') }}</td>
                  <td>{{ $sale->balance }}</td>
                  <td>{{ $sale->payment_status }}</td>
                  <td>{{ $sale->updated_at }}</td>
               </tr>
            @empty
               <x-emptyrow>
                  6
               </x-emptyrow>
            @endforelse
            </tbody>
         </table>
         {{ $vansalesTotal->links() }}
      </div>
   </div>
</section>
</div>

