@extends('layouts.app')
{{-- page header --}}
@section('title', 'Restock History')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"><i data-feather="list"></i>Product Restocking History {{$product->name}} </h2>
               {{--               <h2 class="content-header-title float-start mb-0">Edit Warehouse </h2>--}}
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="{{ url()->previous()}}">Products</a></li>
                     <li class="breadcrumb-item active">Restock History</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   @livewire('warehousing.restockedhistory', ['productid'=>$product->id, 'warehouse'=>$warehousecode])
@endsection
