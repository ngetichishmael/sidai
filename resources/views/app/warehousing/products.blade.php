@extends('layouts.app')
{{-- page header --}}
@section('title','Inventory')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-8">
         <h2 class="page-header"><i data-feather="list"></i> Inventory for {!! $warehouse->name !!} </h2>
      </div>
      @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "Super Admin")
         <div class="col-md-4">
            <center>
               <a href="{!! route('products.create') !!}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New Products</a>
            </center>
         </div>
      @endif
   </div>
   <!-- end breadcrumb -->
   <!-- begin page-header -->

   <!-- end page-header -->
   @include('partials._messages')
   <div>
      <div class="row mb-1">
         <div class="col-md-9">
            <label for=""></label>
            <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Search Product">
         </div>

         @if(Auth::check() && Auth::user()->account_type == "Admin")
            <div class="col-md-3">
               <label for="">Items Per</label>
               <select wire:model="perPage" class="form-control">`
                  <option value="10" selected>10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
               </select>
            </div>
      </div>
      <div class="card card-default">
         <div class="card-body">
            <table class="table table-striped table-bordered" style="font-size: small">
               <thead>
                <tr>
                    <th width="1%">#</th>
                    <th>Name</th>
                    <th>Wholesale Price ksh:</th>
                    <th>Distributor Price ksh:</th>
                    <th>Retail Price ksh:</th>
                    <th>Current Stock</th>
                    @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "nsm")
                     <th>Actions</th>
                  @endif
                </tr>
               </thead>
               <tbody>
               @endif
               @foreach($products as $key => $product)
                  @if(Auth::check() && Auth::user()->account_type == "Admin" ||
                    (Auth::check() && Auth::user()->account_type == "nsm" && \App\Models\warehousing::where("warehouse_code",$product->warehouse_code)))
                     <tr>
                        <td>{!! $key + 1 !!}</td>
                        <td>{!! $product->product_name !!}</td>
                        <td>
                            {{ number_format((float) $product->ProductPrice()->pluck('buying_price')->implode('')) }}
                        </td>
                        <td>
                            {{ number_format((float) $product->ProductPrice()->pluck('buying_price')->implode('')) }}
                        </td>
                        <td>
                            {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
                        </td>
                        <td>

                            {{ $product->Inventory()->pluck('current_stock')->implode('') }}
                        </td>

                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                <span>Re-stock</span>
                            </a>
                           <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                <span>Re-stock</span>
                            </a>
                        </td>
                    </tr>
                  @endif
               @endforeach

               </tbody>
            </table>

         </div>
      </div>

@endsection
