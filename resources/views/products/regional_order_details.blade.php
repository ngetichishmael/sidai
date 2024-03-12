
@extends('layouts.app')
{{-- page header --}}
@section('title','Allocated items')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Regional | Orders</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>


   <div class="card card-default">
    <div class="card-body">
        <div class="card-datatable table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <th width="1%">#</th>
                    <th>Order Code</th>
                    <th>Customer Name</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Quantity</th>
                    <th>Value</th>
                    <th>Created On</th>
                    
                </thead>
                <tbody>
                    @forelse($orders as $count => $order)
                        <td>{{ $count + 1 }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ $order->Customer->customer_name }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td><span class="badge btn-outline-primary">{{ $order->order_status }}</span></td>
                        <td>{{ $order->qty }}</td>
                        <td>{{ $order->price_total }}</td>
                        <td>{{ $order->created_at->format('F j, Y') }}</td>
                        </tr>
                    @empty
                        <div>
                            <tr>
                                <td colspan="10" class="text-center"> No product(s) Found ...</td>
                            </tr>
                        </div>
                    @endforelse
                </tbody>
            </table>
            {{-- @if (!empty($orders))
                <div>
                    {{ $orders->links() }}
                </div>
            @endif --}}

        </div>
    </div>
</div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
    
   
