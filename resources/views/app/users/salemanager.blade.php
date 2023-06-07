@extends('layouts.app')
{{-- page header --}}
@section('title','Sales Managers')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-9">
         <h2 class="page-header">Sales Managers </h2>
      </div>
      <div class="col-md-3">
         <center>
            <a href="{!! route('user.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white"><i data-feather="user-plus"></i> Add User</a>
         </center>
      </div>
   </div>
   <!-- end breadcrumb -->
   @livewire('users.salemanager')
@endsection
{{-- page scripts --}}
@section('script')

@endsection
