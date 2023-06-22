@extends('layouts.app')
{{-- page header --}}
@section('title','Target')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-8">
               <h2 class="content-header-title float-start mb-0">Targets | Reports</h2>
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
   <div class="row">
      <div class="card">
         <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
             <div class="col-md-4">
                 <div class="form-group">
                     <label for="validationTooltip01">From:</label>
                     <input wire:model="start" name="startDate" type="date" class="form-control"
                         id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                 </div>
             </div>
             <div class="col-md-4">
                 <div class="form-group">
                     <label for="validationTooltip01">To:</label>
                     <input wire:model="end" name="startDate" type="date" class="form-control"
                         id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                 </div>
             </div>
         </div>
         </div>
         @livewire('target.index')

   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

