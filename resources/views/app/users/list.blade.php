@extends('layouts.app')
{{-- page header --}}
@section('title','User Roles')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Users Roles List</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
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
                       <th width="20%">User Role</th>
                       <th class="text-center" width="15.5%">Actions</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($lists as $list)
                    <tr>
                       <td>{!! $count++ !!}</td>
                       <td>{!! $list !!}</td>
                       <td>
                          <div class="d-flex" style="gap:20px">
                             @if($list == 'Admin')
                                <a href="{{ route('users.index') }}" class="btn btn-info btn-sm">View</a>
                            @elseif($list == 'Technical-Sales-Agent')
                                <a href="{{ route('technical-sales-agent') }}" class="btn btn-info btn-sm">View</a>
                                @elseif($list == 'Sale-Manager')
                                <a href="{{ route('sale-manager') }}" class="btn btn-info btn-sm">View </a>
                                @elseif($list == 'Manager')
                                <a href="{{ route('manager') }}" class="btn btn-info btn-sm">View</a>
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

