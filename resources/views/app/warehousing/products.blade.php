@extends('layouts.app')
{{-- page header --}}
@section('title','Inventory')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"><i data-feather="list"></i> Warehouse {!! $warehouse->name ?? '' !!} </h2>
{{--               <h2 class="content-header-title float-start mb-0">Edit Warehouse </h2>--}}
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="{{route('warehousing.index')}}">Warehouses</a></li>
                     <li class="breadcrumb-item active">Inventory</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
      <div class="row mb-2">
      <div class="col-md-4">
{{--         <h2 class="page-header"><i data-feather="list"></i> Inventory for Warehouse {!! $warehouse->name !!} </h2>--}}
      </div>
      @if(Auth::check() && Auth::user()->account_type == "Admin" || Auth::check() && Auth::user()->account_type == "NSM" || Auth::check() && Auth::user()->account_type == "RSM")

            <div class="col-md-8">
               <a href="{!! route('products.create') !!}" class="btn btn-success btn-sm ml-1"><i data-feather="plus"></i> Add New Products</a>
               <a href="{!! route('products.import') !!}" class="btn btn-secondary btn-sm mr-1"><i data-feather="arrow-up-circle"></i> Import Products</a>
               <a href="{{ route('products.bulkUpdateForm', ['warehouse'=>$warehouse->warehouse_code]) }}" class="btn btn-primary btn-sm"><i data-feather="refresh-cw"></i> Bulk Prices Updates</a>
               <a href="" class="btn btn-primary btn-sm"><i data-feather="upload"></i> Exports</a>
            {{--TODO :Export products--}}
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
