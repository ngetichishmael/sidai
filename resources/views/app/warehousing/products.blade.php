@extends('layouts.app')
{{-- page header --}}
@section('title','Inventory')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-8">
         <h2 class="page-header"><i data-feather="list"></i> Inventory for Warehouse {!! $warehouse->name !!} </h2>
      </div>
      @if(Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM")
         <div class="col-md-4">
            <center>
               <a href="{!! route('products.create') !!}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add New Products</a>
               <a href="{!! route('products.import') !!}" class="btn btn-success btn-sm"><i class="fas fa-sync-alt"></i> Import Products</a>

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
         @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM" || Auth::check() && Auth::user()->account_type == "Shop-Attendee")
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
                    <th>Wholesale Price</th>
                    <th>Distributor Price</th>
                    <th>Retail Price</th>
                    <th>Current Stock</th>
                    <th>Date</th>
                    <th>time</th>
                    @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM")
                     <th>Actions</th>
                  @endif
                </tr>
               </thead>
               <tbody>
               @endif
               @foreach($products as $key => $product)
                  @if((Auth::check() && Auth::user()->account_type == "RSM") || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "Admin" )
                     <tr>
                        <td>{!! $key + 1 !!}</td>
                        <td>{!! $product->product_name !!}</td>

                           @if ($product->ProductPrice->buying_price ==  0 || 00)
                           <td>{{'Price Not set' }}</td>
                        @else
                              <td>{{number_format((float) $product->ProductPrice->buying_price)}}</td>
                        @endif
                        <td>
                            {{ number_format((float) $product->ProductPrice()->pluck('distributor_price')->implode('')) }}
                        </td>
                        <td>
                            {{ number_format((float) $product->ProductPrice()->pluck('selling_price')->implode('')) }}
                        </td>
                        <td>{{ $product->Inventory()->pluck('current_stock')->implode('') }} </td>
                        <td>{{ $product->updated_at->format('d/m/Y') }}</td>
                        <td>{{ $product->updated_at->format('H:i:s') }}</td>
                        <td>
                            <div class="dropdown" >
                             <button style="background-color: #B6121B;color:white" class="btn btn-md dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                <i data-feather="settings"></i>
                             </button>
                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('products.restock', $product->id) }}" type="button" class="dropdown-item btn btn-sm" style="color: #6df16d;font-weight: bold"><i data-feather="plus"></i> &nbsp;Re-stock</a>
                                @if (Auth::user()->account_type ==="NSM")
                                <a href="{{ route('products.view', $product->id) }}" type="button" class="dropdown-item btn btn-sm" style="color: #7cc7e0; font-weight: bold"><i data-feather="eye"></i>&nbsp; Update Price</a>
                                @endif
                                
                             </div>
                          </div>


                        </td>
                    </tr>
                  @endif
               @endforeach

               </tbody>
            </table>

         </div>
      </div>

@endsection

@section('script')

@endsection
