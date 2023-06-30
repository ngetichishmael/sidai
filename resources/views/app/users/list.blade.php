@extends('layouts.app')
{{-- page header --}}
@section('title','Roles')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Users Roles List</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item active"><a href="/users-Roles">Roles</a></li>
                  </ol>
               </div>
            </div>
            <div class="ml-0 pe-0">
               <center>
                  <a href="{{ route('roles.create') }}" class="btn btn-small" style="background: #B6121B; color: white">Create Role</a>
               </center>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
    <div class="col-md-10">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th width="1%">#</th>
                       <th>Account Types</th>
                       <th width="20%">Number of Users</th>
                       <th>Actions</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($lists as $list)
                    <tr>
                       <td>{!! $count++ !!}</td>
                       <td>{!! $list !!}</td>
                       <td>{{ $counts[$list] }}</td>
                       <td>
                          <div class="d-flex" style="gap:20px">
                             @if($list == 'Admin')
                                <a href="{{ route('users.index') }}" class="btn btn-info btn-sm">View</a>
                            @elseif($list == 'NSM')
                                <a href="{{ route('users.nsm') }}" class="btn btn-info btn-sm">View</a>
                                @elseif($list == 'RSM')
                                <a href="{{ route('rsm') }}" class="btn btn-info btn-sm">View </a>
                             @elseif($list == 'TSR')
                                <a href="{{ route('tsr') }}" class="btn btn-info btn-sm">View </a>
                             @elseif($list == 'TD')
                                <a href="{{ route('td') }}" class="btn btn-info btn-sm">View </a>
                                @elseif($list == 'Shop-Attendee')
                                <a href="{{ route('shop-attendee') }}" class="btn btn-info btn-sm">View</a>
                            @endif
                          </div>
                       </td>
                    </tr>
                    @endforeach
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

