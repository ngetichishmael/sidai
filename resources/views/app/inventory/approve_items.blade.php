@extends('layouts.app')
{{-- page header --}}
@section('title','Approve Items')

@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> Approve | Stock </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Inventory</a></li>
                     <li class="breadcrumb-item active">Stock</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @livewire('productapproval.approve_item',['product_id'=>$id])
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
