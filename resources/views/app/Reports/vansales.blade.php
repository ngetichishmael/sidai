@extends('layouts.app')
{{-- page header --}}
@section('title','Van sales')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Van Sales | Reports</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
            <div class="col-md-3">
               <button type="button" class="btn btn-icon btn-outline-success" wire:click=""
                   wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
                   <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                       data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
               </button>
           </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
    <div class="col-md-12">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Order ID</th>
                       <th>Customer Name</th>
                       <th>User Name</th>
                       <th>User Type</th>
                       <th>Action</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($vansales as $vansale)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $vansale->order_code }}</td>
                     <td>{{ $vansale->Customer->customer_name??'' }}</td>
                     <td>{{ $vansale->User->name??'' }}</td>
                     <td>{{ $vansale->User->account_type??'' }}</td>
                     <td><a href="{{ URL('orders/vansaleitems/'.$vansale->order_code) }}" class="btn" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
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

