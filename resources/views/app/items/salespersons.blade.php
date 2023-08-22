@extends('layouts.app')
{{-- page header --}}
@section('title','Items')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Reconciled | items</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                      <li class="breadcrumb-item "><a href="/stock-Reconciliations">Reconcilition Warehouse</a></li>
                      <li class="breadcrumb-item active"><a href="#">Sales</a></li>
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
                     <th>Sales Person Name</th>
{{--                     <th>Product Name</th>--}}
                     <th>Total Amount</th>
                     <th>Date</th>
                     <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($sales as $key=>$sale)
                     <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $sale->user }}</td>
{{--                        <td>{{ $sale->name }}</td>--}}
                        <td>{{ $sale->total_amount }}</td>
                        <td>{{ $sale->date}}</td>
                        <td><a href="{{ URL('products/reconciled/' . $warehouse) }}" class="btn btn-sm" style="color: white;background-color:rgb(194, 51, 51)">View</a></td>
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

