@extends('layouts.app')
{{-- page header --}}
@section('title','Items')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Suppliers | items</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
    <div class="col-md-8">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Order Code</th>
                       <th>Status</th>
                       <th>Payment Status</th>
                       <th>SKU code</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($orders as $key=>$order)
                  <tr>
                     <td>{{ $key+1 }}</td>
                     <td>{{ $order->order_code }}</td>
                     <td>{{ $order->order_status }}</td>
                     <td>{{ $order->payment_status }}</td>
                     <td></td>
                 </tr>
                  @endforeach
                   
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

