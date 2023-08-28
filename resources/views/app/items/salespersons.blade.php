@extends('layouts.app')
{{-- page header --}}
@section('title','Stock Recon sales person')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Stock Reconciliations Sales Person</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item "><a href="/stock-Reconciliations">Reconcilition</a></li>
                        <li class="breadcrumb-item active"><a href="#">Sales Person</a></li>
                     </ol>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
      <div class="col-md-10">
         <div class="card card-inverse">
            <div class="card-body">
               <table id="data-table-default" class="table table-striped table-bordered">
                  <thead>
                  <tr>
                     <th>#</th>
                     <th>Sales Person</th>
                     <th>Total Amount</th>
                     <th>Warehouse</th>
                     <th>Date</th>
                     <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($sales as $key=>$sale)
                     <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $sale->user }}</td>
                        <td>{{ $sale->total_amount }}</td>
                        <td>{{ $warehouse_name }}</td>
                        <td>{{ $sale->date }}</td>
                        <td><a href="{{ URL('products/reconciled/' . $sale->id) }}" class="btn btn-sm" style="color: white;background-color:rgb(194, 51, 51)">View Products</a></td>
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

