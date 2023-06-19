@extends('layouts.app')
{{-- page header --}}
@section('title','Pending Deliveries')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Pending Deliveries </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/sokoflowadmin">Home</a></li>
                     <li class="breadcrumb-item active"><a href="#">Pending Deliveries List</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   @livewire('orders.pendingdeliveries')
@endsection
{{-- page scripts --}}
@section('script')

@endsection
