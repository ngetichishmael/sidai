@php
    use Illuminate\Support\Str;
@endphp
<div>
    <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50">
        <div class="col-md-6">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control" placeholder="Enter customer name">
        </div>
        <div class="col-md-2">
            <label for="">Items Per</label>
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
                        <th>Customer</th>
                        <th>Region</th>
                        <th>Route</th>
                        <th>Sales Person</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>QTY</th>
                        <th>Date</th>
                       <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($orders as $count => $order)

                            <tr>
                                {{-- @dd($order->id) --}}
                                <td>{{ $count + 1 }}</td>
                                <td title="{{ $order->Customer->customer_name ?? null }}">
                                    {{ Str::limit($order->Customer->customer_name ?? null, 20) }}</td>
                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">
                                    {{ Str::limit($order->Customer->Area->Subregion->name ?? null, 20) }}</td>
                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">
                                    {{ Str::limit($order->Customer->Area->name ?? null, 20) }}</td>
                                <td title="{{ $order->User->name ?? null }}">
                                    {{ Str::limit($order->User->name ?? null, 10) }}</td>
                                <td>ksh {{ number_format($order->Order->price_total) }}</td>
                                <td>ksh {{ number_format($order->Order->balance) }}</td>
                               <td>{{ $order->DeliveryItems->first()->allocated_quantity ?? '' }}</td>
{{--                               <td>{{$order->Order->qty}}</td>--}}
                               <td>{{ $order->created_at }}</td>
{{--                                <td>{{ $order->order_status }}</td>--}}

                               <td>
                                  <div class="dropdown">
                                     <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather='settings'></i>
                                     </button>
                                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{!! route('orders.pendingdetails', $order->order_code) !!}">View</a>
                                        @if ($order->order_status === 'CANCELLED')
                                           <a wire:click.prevent="activate({{ $order->id }})"
                                              onclick="confirm('Are you sure you want to REINSTATE this Order by id {{ $order->order_code }}?') || event.stopImmediatePropagation()"
                                              type="button" class="dropdown-item btn btn-sm" style="color: lightgreen">Reinstate</a>
                                        @else
                                           <a wire:click.prevent="deactivate({{ $order->id }})"
                                              onclick="confirm('Are you sure you want to CANCEL this Order {{ $order->order_code }}?') || event.stopImmediatePropagation()"
                                              type="button" class="dropdown-item btn btn-sm" style="color: orangered">Cancel</a>
                                        @endif
                                     </div>
                                  </div>
                               </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $orders->links() !!}
        </div>
    </div>
    @section('scripts')
    @endsection
