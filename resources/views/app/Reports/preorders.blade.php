@extends('layouts.app')
{{-- page header --}}
@section('title','Pre orders')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Pre Orders | Reports</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
            <div class="col-md-3">
               <button type="button" class="btn btn-icon btn-outline-success" wire:click=""
                   wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
                   <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                       data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
               </button>
           </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   @livewire('reports.preorder')
@endsection
{{-- page scripts --}}
@section('script')

@endsection

