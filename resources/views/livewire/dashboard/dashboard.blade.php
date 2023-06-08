    <div>
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
            <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="validationTooltip01">Start Date</label>
                        <input wire:model="start" name="startDate" type="date" class="form-control"
                            id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="validationTooltip01">End Date</label>
                        <input wire:model="end" name="startDate" type="date" class="form-control"
                            id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                    </div>
                </div>
            </div>
            </div>

        </div>
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics</h4>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0">Default Shows Monthly Report</p>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body statistics-body mt-0">
                        <div class="row">
{{--                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">--}}
{{--                                <a href="#vansalesSection">--}}
{{--                                    <div class="media">--}}
{{--                                        <div class="avatar bg-light-primary mr-2">--}}
{{--                                            <div class="avatar-content">--}}
{{--                                                <span--}}
{{--                                                    class="material-symbols-outlined">production_quantity_limits</span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="media-body my-auto">--}}
{{--                                            <h4 class="font-weight-bolder mb-0">{{ number_format($vansales) }}</h4>--}}
{{--                                            <p class="card-text font-small-3 mb-0" style="color: black">Van Sales</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </a>--}}
{{--                            </div>--}}
                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">inventory</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder"> &nbsp;{{ number_format($vansales) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Van Sales</p>
                                 </div>
                              </a>
                           </div>

                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">shopping_cart</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">{{ number_format($preorder) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Pre-Orders</p>
                                 </div>
                              </a>
                           </div>
                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">arrow_forward</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">{{ number_format($customersCount) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Customers</p>
                                 </div>
                              </a>
                           </div>
                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">order_approve</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">{{ number_format($orderfullmentbydistributors) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Distributor-Orders</p>
                                 </div>
                              </a>
                           </div>

                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">local_shipping</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">{{ number_format($orderfullment) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Deliveries</p>
                                 </div>
                              </a>
                           </div>
                           <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                              <a href="#vansalesSection" class="d-flex align-items-center">
                                 <div class="avatar bg-light-primary">
                                    <div class="avatar-content">
                                       <span class="material-symbols-outlined">people</span>
                                    </div>
                                 </div> &nbsp;&nbsp;
                                 <div class="media-body my-auto pl-3 ml-3">
                                    <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">{{ number_format($activeAll) }}</h4>
                                    <p class="card-text font-small-3 mb-0 font-medium-1" style="color: rgba(71,75,79,0.76)">Users</p>
                                 </div>
                              </a>
                           </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics Card -->
            <div class="row">
                <div class="col-md-12">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-8">
                                <div class="card">
{{--                                @livewire('dashboard.brand-chart')--}}
{{--                                   @dump($preOrdersLabels)--}}
                                   <canvas id="lineChart" width="800" height="400"></canvas>

                                   <script>
                                      const data = @json($graphdata);

                                      const labels = data.map(entry => entry.month);

                                      const preOrderCounts = data.map(entry => entry.preOrderCount);
                                      const deliveryCounts = data.map(entry => entry.deliveryCount);

                                      const ctx = document.getElementById('lineChart').getContext('2d');
                                      new Chart(ctx, {
                                         type: 'line',
                                         data: {
                                            labels: labels,
                                            datasets: [
                                               {
                                                  label: 'Pre Orders',
                                                  data: preOrderCounts,
                                                  borderColor: 'rgb(255,69,0)',
                                                  // backgroundColor: 'rgba(255, 69, 0, 0.2)',
                                                  fill: false
                                               },
                                               {
                                                  label: 'Deliveries',
                                                  data: deliveryCounts,
                                                  borderColor: 'rgb(21,116,239)',
                                                  // backgroundColor: 'rgba(243,75,84,0.3)',
                                                  fill: false
                                               }
                                            ]
                                         },
                                         options: {
                                            responsive: true,
                                            scales: {
                                               x: {
                                                  display: true,
                                                  title: {
                                                     display: true,
                                                     text: 'Months'
                                                  }
                                               },
                                               y: {
                                                  beginAtZero: true,
                                                  // min: 0,
                                                  // max: Math.ceil(Math.max(...preOrderCounts, ...deliveryCounts) / 50) * 50,
                                                  // max: 10000,
                                                  stepSize: 50,
                                                  display: true,
                                                  title: {
                                                     display: true,
                                                     text: 'Count'
                                                  }
                                               }
                                            }
                                         }
                                      });
                                   </script>
                                </div>

                            </div>
{{--                            <div class="col-4">--}}
{{--                                <div class="card">--}}
{{--                                @livewire('dashboard.catergory-chart')--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </div>
                    {{-- <div class="col-12">
                        <section>
                            <div class="row">
                                <div class=" col-6 border border-primary">
                                    @livewire('dashboard.individual-targets')
                                </div>
                                <div class=" col-6 border border-primary">
                                    @livewire('dashboard.percentage-targets')
                                </div>
                            </div>
                        </section>
                    </div> --}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-6">--}}
{{--                                    <h4>Money Collected</h4>--}}
{{--                                    <table class="table table-striped table-bordered">--}}
{{--                                        <thead>--}}
{{--                                            <th>Total Collected</th>--}}
{{--                                            <th width="20%"></th>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                            <tr class="table-success">--}}
{{--                                                <td>Cash</td>--}}
{{--                                                <td>{{ number_format($Cash) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-success">--}}
{{--                                                <td>Mpesa</td>--}}
{{--                                                <td>{{ number_format($Mpesa) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-success">--}}
{{--                                                <td>Cheque</td>--}}
{{--                                                <td>{{ number_format($Cheque) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-success">--}}
{{--                                                <td>Total Reconciled</td>--}}
{{--                                                <td>{{ number_format($total) }}</td>--}}
{{--                                            </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                                <div class="col-6">--}}
{{--                                    <h4>Receivables Aging</h4>--}}
{{--                                    <table class="table table-striped table-bordered">--}}
{{--                                        <thead>--}}
{{--                                            <th>Total Collected</th>--}}
{{--                                            <th width="20%"></th>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                            <tr class="table-warning">--}}
{{--                                                <td>Today</td>--}}
{{--                                                <td>{{ number_format($daily) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-warning">--}}
{{--                                                <td>0-7days</td>--}}
{{--                                                <td>{{ number_format($weekly) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-warning">--}}
{{--                                                <td>0-30Days</td>--}}
{{--                                                <td>{{ number_format($monthly) }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr class="table-warning">--}}
{{--                                                <td>Total</td>--}}
{{--                                                <td>{{ number_format($sumAll) }}</td>--}}
{{--                                            </tr>--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
            <section class="app-user-list" id="creditors">
                <div class="card">
                    <h5 class="card-header">Overdue Creditors</h5>
                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="selectSmall">Select Per Page</label>
                                <select wire:model='perVansale' class="form-control form-control-sm"
                                    id="selectSmall">
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
{{--                                    <th>Order Code</th>--}}
                                    <th>Customer Name</th>
                                    <th>Balance </th>
{{--                                    <th>Payment Status</th>--}}
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
            <section class="app-user-list" id="vansalesSection">
                <div class="card">
                    <h5 class="card-header">Total Vansales</h5>
                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="selectSmall">Select Per Page</label>
                                <select wire:model='perVansale' class="form-control form-control-sm"
                                    id="selectSmall">
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
                                <select wire:model='perPreorder' class="form-control form-control-sm"
                                    id="selectSmall">
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
            <section class="app-user-list" id="orderFulfillmentSection">
                <div class="card">
                    <h5 class="card-header">Order Fulfilment</h5>
                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="selectSmall">Select Per Page</label>
                                <select wire:model='perOrderFulfilment' class="form-control form-control-sm"
                                    id="selectSmall">
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
{{--            <section class="app-user-list" id="activeUsersSection">--}}
{{--                <div class="card">--}}
{{--                    <h5 class="card-header">Active Users</h5>--}}
{{--                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">--}}
{{--                        <div class="col-md-2">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="selectSmall">Select Per Page</label>--}}
{{--                                <select wire:model='perActiveUsers' class="form-control form-control-sm"--}}
{{--                                    id="selectSmall">--}}
{{--                                    <option value="10">10</option>--}}
{{--                                    <option value="20">20</option>--}}
{{--                                    <option value="50">50</option>--}}
{{--                                    <option value="100">100</option>--}}
{{--                                    <option value="500">500</option>--}}
{{--                                    <option value="1000">1000</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                --}}{{-- @dd($activeUserTotal) --}}
{{--                <div class="card">--}}
{{--                    <div class="pt-0 card-datatable table-responsive">--}}
{{--                        <table class="table">--}}
{{--                            <thead class="thead-light">--}}
{{--                                <tr>--}}
{{--                                    <th>ID</th>--}}
{{--                                    <th>Sales Associates</th>--}}
{{--                                    <th>Email</th>--}}
{{--                                    <th>Status</th>--}}
{{--                                    <th>Edit</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                @forelse ($activeUserTotal as $key=>$user)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $key + 1 }}</td>--}}
{{--                                        <td>{{ $user->user()->pluck('name')->implode('') }}</td>--}}
{{--                                        <td>{{ $user->user()->pluck('email')->implode('') }}</td>--}}
{{--                                        <td>{{ $user->user()->pluck('status')->implode('') }}</td>--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ route('user.edit', $user->user_code) }}"--}}
{{--                                                class="btn btn-primary btn-sm">Edit</a>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @empty--}}
{{--                                    <x-emptyrow>--}}
{{--                                        6--}}
{{--                                    </x-emptyrow>--}}
{{--                                @endforelse--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                        {{ $activeUserTotal->links() }}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </section>--}}
{{--            <section class="app-user-list" id="visitsSection">--}}
{{--                <div class="card">--}}
{{--                    <h5 class="card-header">Visits</h5>--}}
{{--                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">--}}
{{--                        <div class="col-md-2">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="selectSmall">Select Per Page</label>--}}
{{--                                <select wire:model='perVisits' class="form-control form-control-sm" id="selectSmall">--}}
{{--                                    <option value="10">10</option>--}}
{{--                                    <option value="20">20</option>--}}
{{--                                    <option value="50">50</option>--}}
{{--                                    <option value="100">100</option>--}}
{{--                                    <option value="500">500</option>--}}
{{--                                    <option value="1000">1000</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="card">--}}
{{--                    <div class="pt-0 card-datatable table-responsive">--}}
{{--                        <table class="table">--}}
{{--                            <thead class="thead-light">--}}
{{--                                <tr>--}}
{{--                                    <th>ID</th>--}}
{{--                                    <th>Customer</th>--}}
{{--                                    <th>Sales Associates</th>--}}
{{--                                    <th>Status</th>--}}
{{--                                    <th>Action</th>--}}
{{--                                </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                                @forelse ($visitsTotal as $key=>$user)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $key + 1 }}</td>--}}
{{--                                        <td>{{ $user->customer()->pluck('customer_name')->implode('') }}</td>--}}
{{--                                        <td>{{ $user->user()->pluck('name')->implode('') }}</td>--}}
{{--                                        <td>{{ $user->user()->pluck('status')->implode('') }}</td>--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ route('customer.edit', $user->customer_id) }}"--}}
{{--                                                class="btn btn-sm btn-primary">Edit</a>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @empty--}}
{{--                                    <x-emptyrow>--}}
{{--                                        6--}}
{{--                                    </x-emptyrow>--}}
{{--                                @endforelse--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                        {{ $visitsTotal->links() }}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </section>--}}
            <section class="app-user-list" id="buyingCustomersSection">
                <div class="card">
                    <h5 class="card-header">Buying Customers</h5>
                    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="selectSmall">Select Per Page</label>
                                <select wire:model='perBuyingCustomer' class="form-control form-control-sm"
                                    id="selectSmall">
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
                                @forelse ($customersCountTotal as $key=>$sale)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $sale->order_code }}</td>
                                        <td>{{ $sale->User()->pluck('name')->implode('') }}</td>
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
                        {{ $customersCountTotal->links() }}
                    </div>
                </div>
            </section>

        </div>
