@extends('layouts.app')
{{-- page header --}}
@section('title','Delivery')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"> History Delivery </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="sokoflowadmin">Home</a></li>
                     <li class="breadcrumb-item"><a href="{!! route('delivery.index') !!}">Delivery</a></li>
                     <li class="breadcrumb-item active">List</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   @livewire('delivery.index')
@endsection
{{-- page scripts --}}
