@php
    use Illuminate\Support\Str;
@endphp
<div>
   <div class="col-xl-12 col-md-12 col-12">
      <div class="card">
         <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
               {{-- <div class="form-group">
                  <label for="fromDate">From:</label>
                  <input type="date" id="fromDate" wire:model="fromDate"
                         name="startDate" type="date" class="form-control" placeholder="YYYY-MM-DD HH:MM" required>
               </div> --}}
               <div class="col-md-3">
                <label for="validationTooltip01">Start Date</label>
                <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
               {{-- <div class="form-group">
                  <label for="validationTooltip01">End Date</label>
                  <input type="date" id="toDate" wire:model="toDate" name="endDate" type="date" class="form-control"
                         placeholder="YYYY-MM-DD HH:MM" required />
               </div> --}}
               <div class="col-md-3">
                <label for="validationTooltip01">End Date</label>
                <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
                    placeholder="YYYY-MM-DD HH:MM" required />
            </div>
         </div>
      </div>
   </div>
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
    <br>
@include('partials.stickymenu')
<br>
    <div class="card card-default">
        <div class="card-body">
            <div class="card-datatable">
                <table class="table table-striped table-bordered zero-configuration table-responsive">
                    <thead>
                        <th width="1%">#</th>
                        <th>Customer</th>
                        <th>Sales Person</th>
                        <th>Sub-Region</th>
                        <th>Route</th>
                        <th>Amount (Ksh.)</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($deliveries as $count => $order)
                            <tr>
                                <td>{{ $count + 1 }}</td>
                                <td title="{{ $order->Customer->customer_name ?? null }}">
                                    {{ Str::limit($order->Customer->customer_name ?? null, 30) }}</td>
                               <td title="{{ $order->User->name ?? null }}">
                                  {{ Str::limit($order->User->name ?? null, 20) }}</td>
                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">
                                    {{ Str::limit($order->Customer->Area->Subregion->name ?? null, 20) }}</td>
                                <td title="{{ $order->Customer->Area->Subregion->name ?? null }}">
                                    {{ Str::limit($order->Customer->Area->name ?? null, 20) }}</td>
                                <td>{{ number_format($order->price_total) }}</td>
                               <td>{{$order->created_at}}</td>
                               <td>
                                  <div class="dropdown">
                                     <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i data-feather='settings'></i>
                                     </button>
                                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{!! route('details.order', $order->order_code) !!}">View</a>
                                     </div>
                                  </div>
                               </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! $deliveries->links() !!}
        </div>
    </div>
    @section('scripts')
    @endsection


