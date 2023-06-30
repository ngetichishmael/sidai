@extends('layouts.app')
{{-- page header --}}
@section('title','Products Approval')

@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Requisitions | Approval</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
{{--                     <li class="breadcrumb-item"><a href="#">Products</a></li>--}}
                     <li class="breadcrumb-item active">Products Approval</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @livewire('productapproval.approval')

@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
