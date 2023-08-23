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
   @livewire('warehousing.products', ['warehouse'=>$warehouse->warehouse_code])
@endsection

@section('script')

@endsection
