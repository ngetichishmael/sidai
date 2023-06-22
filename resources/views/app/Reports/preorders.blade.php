@extends('layouts.app')
{{-- page header --}}
@section('title','Pre orders')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Pre Orders | Reports</h2>
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
                       <th>Sales Rep</th>
                       <th>Region</th>
                       <th>Sub Region</th>
                       <th>Status</th>
                       <th>Created Date</th>
                       <th>Action</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($preorders as $preorder)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $preorder->order_code }}</td>
                        <td>{{ $preorder->Customer->customer_name??'' }}</td>
                        <td>{{ $preorder->User->name??'' }}</td>
                        <td>{{ $preorder->User->Region->name??'' }}</td>
                        <td>{{ $preorder->User->Subregion->name??'' }}</td>
                        <td>{{ $preorder->order_status ??'' }}</td>
                        <td>{{ $preorder->created_at->format('d/m/Y')??'' }}</td>
                        <td><a href="{{ URL('orders/items/'.$preorder->order_code) }}" class="btn btn-sm" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
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

