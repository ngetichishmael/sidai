@extends('layouts.app')
{{-- page header --}}
@section('title','Technical Sales Representative')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="row mb-2">
      <div class="col-md-9">
         <h2 class="page-header"> Technical Sales Representative </h2>
      </div>
      <div class="col-md-3">
         <center>
            <a href="{!! route('user.create') !!}" class="btn btn-primary btn-sm"><i class="fa fa-user-plus"></i> Add User</a>
         </center>
      </div>
   </div>
   <!-- end breadcrumb -->
   @livewire('users.tsr')
@endsection
{{-- page scripts --}}
@section('script')

@endsection
