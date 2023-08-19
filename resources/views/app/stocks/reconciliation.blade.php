@extends('layouts.app')
{{-- page header --}}
@section('title','Stock Recon')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Stock Reconciliations</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item "><a href="/stock-Reconciliations">Reconcilition Warehouse</a></li>
                        <li class="breadcrumb-item active"><a href="#">Sales</a></li>
                     </ol>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   @livewire('stocks.reconciliation')
@endsection
{{-- page scripts --}}
@section('script')

@endsection

