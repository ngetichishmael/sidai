@extends('layouts.app')
{{-- page header --}}
@section('title', '{{description->description}}')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">{{$description->description}}</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item active"><a href="/users-Roles">Roles List</a></li>
                     <li class="breadcrumb-item active"><a href="#">{{$description->description}} List</a></li>
                  </ol>
               </div>
               <div class="row">
               <div class="col-md-9">
               </div>
               <div class="col-md-3">
                  <center>
                     <a href="{!! route('user.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white"><i data-feather="user-plus"></i> Add Users</a>
                  </center>
               </div>
               </div>
            </div>
         </div>
      </div>
   </div>
    <!-- end breadcrumb -->
    @livewire('users.index',  ['role' => $role])
@endsection
{{-- page scripts --}}
@section('script')

@endsection
