@php
    use Illuminate\Support\Str;
@endphp
<div>
   <div class="col-xl-12 col-md-12 col-12">
      <div class="card">
         <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
            <div class="col-md-4">
               <div class="form-group">
                  <label for="fromDate">From:</label>
                  <input type="date" id="fromDate" wire:model="fromDate"
                         name="startDate" type="date" class="form-control" placeholder="YYYY-MM-DD HH:MM" required>
               </div>
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label for="validationTooltip01">End Date</label>
                  <input type="date" id="toDate" wire:model="toDate" name="endDate" type="date" class="form-control"
                         placeholder="YYYY-MM-DD HH:MM" required />
               </div>
            </div>
         </div>
      </div>
   </div>
      <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
         <div class="col-md-2">
            <div>
               <label for="">Filter By Status: </label>
               <select wire:model="statusFilter" class="form-control">
                  <option value="">All Statuses</option>
                  <option value="Pending Delivery">Pending Orders</option>
                  <option value="Complete Delivery">Complete Delivery</option>
                  <option value="Waiting acceptance">Waiting acceptance</option>
                  <option value="Partially Delivered">Partially Delivered</option>
               </select>
            </div>
         </div></div>
    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50">
        <div class="col-md-6">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control" placeholder="Enter customer name">
        </div>
        <div class="col-md-2">
            <label for="">Items Per: </label>
            <select wire:model="perPage" class="form-control">`
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="75">75</option>
                <option value="100">100</option>
                <option value="100">200</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-icon btn-outline-success" wire:click="export"
                wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
                <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                    data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
            </button>
        </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <div class="card-datatable">
                <table class="table table-striped table-bordered zero-configuration table-responsive">
                    <thead>
                        <th width="1%">#</th>
                        <th>Distributor</th>
                        <th>Customer</th>
                        <th>Sales Person</th>
                        <th>Amount (Ksh.)</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($pendingorders as $count => $order)

                            <tr>
                                {{-- @dd($order->id) --}}
                                <td>{{ $count + 1 }}</td>
                               <td>{{ $order->distributor()->pluck('name')->implode('') }}</td>
                                <td title="{{ $order->Customer->customer_name ?? null }}">
                                    {{ Str::limit($order->Customer->customer_name ?? null, 30) }}</td>
{{--                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">--}}
{{--                                    {{ Str::limit($order->Customer->Area->Subregion->name ?? null, 20) }}</td>--}}
{{--                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">--}}
{{--                                    {{ Str::limit($order->Customer->Area->name ?? null, 20) }}</td>--}}
                                <td title="{{ $order->User->name ?? null }}">
                                    {{ Str::limit($order->User->name ?? null, 20) }}</td>
                                <td>{{ number_format($order->price_total) }}</td>
{{--                                <td>{{ number_format($order->balance) }}</td>--}}
                               <td>{{$order->created_at}}</td>
                               @php
                                  $orderStatus = strtolower($order->order_status);
                               @endphp

                               @if ($orderStatus == 'pending delivery')
                                  <td class="pending-order">Pending Order</td>
                               @elseif ($orderStatus == 'complete delivery')
                                  <td class="delivered-order">{{ $order->order_status }}</td>
                               @elseif ($orderStatus == 'waiting acceptance')
                                  <td class="waiting-acceptance">{{ $order->order_status }}</td>
                               @elseif ($orderStatus == 'partially delivery')
                                  <td class="partial-delivery">{{ $order->order_status }}</td>
                               @elseif ($orderStatus == 'not delivered')
                                  <td class="not-delivered">{{ $order->order_status }}</td>
                               @else
                                  <td>{{ $order->order_status }}</td>
                               @endif


                               {{--                               @if($order->order_status == 'Pending Delivery') <td>{{'Pending Order'}}</td>--}}
{{--                               @else--}}
{{--                               {{ $order->order_status}}--}}
{{--                               @endif--}}

{{--                                <td>{{ $order->order_status }}</td>--}}
{{--                                <td>--}}
{{--                                    <a href="{!! route('orders.details', $order->order_code) !!}" class="btn btn-warning btn-sm">View</a>--}}
{{--                                    @if ($order->order_status === 'CANCELLED')--}}
{{--                                        <button wire:click.prevent="activate({{ $order->id }})"--}}
{{--                                            onclick="confirm('Are you sure you want to REINSTATE this Order by id {{ $order->order_code }}?')||event.stopImmediatePropagation()"--}}
{{--                                            type="button" class="btn btn-success btn-sm">Reinstate</button>--}}
{{--                                    @else--}}
{{--                                        <button wire:click.prevent="deactivate({{ $order->id }})"--}}
{{--                                            onclick="confirm('Are you sure you want to CANCEL this Order {{ $order->order_code }}?')||event.stopImmediatePropagation()"--}}
{{--                                            type="button" class="btn btn-danger btn-sm">Cancel</button>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
                               <td>
                                  <div class="dropdown">
                                     <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather='settings'></i>
                                     </button>
                                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{!! route('orders.distributorsdetails', $order->order_code) !!}">View</a>
{{--                                        @if ($order->order_status === 'CANCELLED')--}}
{{--                                           <a wire:click.prevent="activate({{ $order->id }})"--}}
{{--                                                   onclick="confirm('Are you sure you want to REINSTATE this Order by id {{ $order->order_code }}?') || event.stopImmediatePropagation()"--}}
{{--                                                   type="button" class="dropdown-item btn btn-sm" style="color: lightgreen">Reinstate</a>--}}
{{--                                        @else--}}
{{--                                           <a wire:click.prevent="deactivate({{ $order->id }})"--}}
{{--                                                   onclick="confirm('Are you sure you want to CANCEL this Order {{ $order->order_code }}?') || event.stopImmediatePropagation()"--}}
{{--                                                   type="button" class="dropdown-item btn btn-sm" style="color: orangered">Cancel</a>--}}
{{--                                        @endif--}}
                                     </div>
                                  </div>
                               </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $pendingorders->links() !!}
        </div>
    </div>
   <style>
      .pending-order {
         color: orange;
      }

      .delivered-order {
         color: green;
      }

      .waiting-acceptance {
         color: blue;

      }

      .partial-delivery {
         color: purple;
      }
      .not-delivered {
         color: orangered;
      }
   </style>
    @section('scripts')
    @endsection
