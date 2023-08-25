@extends('layouts.app')
{{-- page header --}}
@section('title', 'Create Role')

<!-- Bootstrap CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome for icons (optional, as you may already have it) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

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
                     <li class="breadcrumb-item"><a href="/users-Roles">Roles</a></li>
                     <li class="breadcrumb-item"><a href="#">Create Roles</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
      <div class="col-md-9 col-sm-12">
         <div class="card card-inverse">
            <div class="card-body">
               <div class="card-body">
                  <table id="data-table-default" class="table table-striped table-bordered">
                     <thead>
                     <tr>
                        <th>Initials</th>
                        <th>Role name</th>
                        <th>Data Authorized To Access</th>
                        <th>Platform</th>
{{--                        <th>Created by</th>--}}
{{--                        <th>Updated by</th>--}}
                        <th>Actions</th>
                     </tr>
                     </thead>
                     <tbody class="font-small-3">
                     @if(empty($roles))
                        <div class="col-span-5"></div>
                     @endif
                     @foreach ($roles as $key => $role)
                        <tr>
                           <td>{{ $role->name }}</td>
                           <td>{{ $role->description}}</td>
                           <td>{{ $role->data_access_level}}</td>
                           <td>
                              @foreach ($role->permissions as $permission)
                                 <span style="display: inline-block; padding: 0.1rem 1rem; margin-right: 0.2rem; background-color: #007bff; color: #fff; border-radius: 0.35rem;">{{ ucfirst($permission->name) }}</span>
                              @endforeach
                           </td>
{{--                           <td>{{ $role->CreatedBy->name ?? '' }}</td>--}}
{{--                           <td>{{ $role->UpdatedBy->name ?? '' }}</td>--}}
{{--                           <td><button type="button" class="btn btn-sm" data-toggle="modal" style="background-color: #B6121B;color:white" data-target="#editRoleModal">--}}
{{--                                 Edit Role--}}
{{--                              </button></td>--}}
                           <td>
                              <div class="dropdown" >
                                 <button style="background-color: #B6121B;color:white" class="btn btn-md dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                    <i data-feather="settings"></i>
                                 </button>
                                 <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-sm" style="color: #52b3dc">View</a>
                                    <br/>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm" style="color: darkseagreen">Edit</a>
                                    <br/>
                                    <form action="{{ route('roles.destroy', $role) }}" method="post" class="d-inline">

                                       @csrf
                                       @method('DELETE')
                                       <button type="submit" class="btn btn-sm" style="color: #f66962"
                                               onclick="return confirm('Are you sure you want to delete this role?')">Delete</button>
                                    </form>
                                 </div>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                     </tbody>
                  </table>
               </div>
{{--               {{ $roles->links() }}--}}
            </div>
         </div>
      </div>
      <div class="col-md-3 col-sm-12">
         <div class="card card-default">
            <div class="card-body">
               <div class="card-body">
                  <h4 class="card-title">Add role</h4>
                  {!! Form::open(['route' => 'roles.store']) !!}
                  @csrf
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('name', 'Name', ['class' => 'control-label']) !!}
                     {!! Form::text('name', null, [
                         'class' => 'form-control',
                         'placeholder' => 'Enter Role Name Initials',
                         'required' => '',
                     ]) !!}
                  </div>
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('display_name', 'Display Name', ['class' => 'control-label']) !!}
                     {!! Form::text('display_name', null, [
                         'class' => 'form-control',
                         'placeholder' => 'Enter Role Display Name',
                         'required' => '',
                     ]) !!}
                  </div>
{{--                  <div class="form-group form-group-default required mb-1">--}}
{{--                     {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}--}}
{{--                     {!! Form::text('description', null, [--}}
{{--                         'class' => 'form-control',--}}
{{--                         'placeholder' => 'Enter Role Description',--}}
{{--                     ]) !!}--}}
{{--                  </div>--}}
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('platform', 'Platform', ['class' => 'control-label']) !!}
                     <br>
                     @foreach($permissions as $id => $label)
                        <div class="form-check form-check-inline mt-0.9">
                           {!! Form::checkbox('platform[]', $id, null, ['class' => 'form-check-input', 'id' => 'platform_'.$id]) !!}
                           {!! Form::label('platform_'.$id, $label, ['class' => 'form-check-label']) !!}
                        </div>
                     @endforeach
                  </div>

                  {{--                  @if (!is_null($permissions) && is_array($permissions))--}}
{{--                     <div class="form-group form-group-default required mb-1">--}}
{{--                        {!! Form::label('platform', 'Platform', ['class' => 'control-label']) !!}--}}
{{--                        <br>--}}
{{--                        @foreach($permissions as $permission)--}}
{{--                           <div class="form-check form-check-inline mt-0.9">--}}
{{--                              {!! Form::checkbox('platform[]', $permission['id'], null, ['class' => 'form-check-input', 'id' => 'platform_'.$permission['id']]) !!}--}}
{{--                              {!! Form::label('platform_'.$permission['id'], $permission['label'], ['class' => 'form-check-label']) !!}--}}
{{--                           </div>--}}
{{--                        @endforeach--}}
{{--                     </div>--}}
{{--                  @else--}}
{{--                     <p>No permissions found.</p>--}}
{{--                  @endif--}}

                  {{--                  <div class="form-group form-group-default required mb-1">--}}
{{--                     {!! Form::label('platform', 'Platform', ['class' => 'control-label']) !!}--}}
{{--                     <br>--}}
{{--                     @foreach($permissions as $permission)--}}
{{--                        <div class="form-check form-check-inline mt-0.9">--}}
{{--                           {!! Form::checkbox('platform[]', $permission['id'], null, ['class' => 'form-check-input', 'id' => 'platform_'.$permission['id']]) !!}--}}
{{--                           {!! Form::label('platform_'.$permission['id'], $permission['label'], ['class' => 'form-check-label']) !!}--}}
{{--                        </div>--}}
{{--                     @endforeach--}}
{{--                  </div>--}}
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('data_access_level', 'Data Access Level', ['class' => 'control-label']) !!}
                     {!! Form::select('data_access_level', [
                         'all' => 'All regions',
                         'regional' => 'Regional Data',
                         'subregional' => 'Subregional ',
                         'route' => 'Routes Data',
                     ], null, [
                         'class' => 'form-control',
                         'required' => 'required',
                         'id' => 'data_access_level_select'
                     ]) !!}
                  </div>
                  <div class="mt-4 form-group">
                     <center>
                        <button type="submit" class="btn  submit" style="background-color: #B6121B;color:white"><i data-feather="plus"></i> Add
                           Role</button>
                     </center>
                  </div>
                  {!! Form::close() !!}
               </div>
            </div>

         </div>
         <div class="mt-2 form-group">
            <center>
               <a href="/users-Roles"  class="btn" style="background-color: #fc7d50;color:white"><i data-feather=""></i> Back</a>
            </center>
         </div>
      </div>
   </div>
   <!-- Modal -->
   <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               {!! Form::open(['route' => ['roles.update', $role->id ?? ''], 'method' => 'PUT']) !!}
               @csrf
               <div class="form-group">
                  {!! Form::label('name', 'Name') !!}
                  {!! Form::text('name', $role->name ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Role Name']) !!}
               </div>
               <div class="form-group">
                  {!! Form::label('display_name', 'Display Name') !!}
                  {!! Form::text('display_name', $role->display_name ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Display Name']) !!}
               </div>
               <div class="form-group">
                  {!! Form::label('description', 'Description') !!}
                  {!! Form::text('description', $role->description ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Description']) !!}
               </div>
               <div class="form-group form-group-default required mb-1">
                  {!! Form::label('platform', 'Platform', ['class' => 'control-label']) !!}
                  <br>
                  @foreach($permissions as $id => $label)
                     <div class="form-check form-check-inline mt-0.9">
                        {!! Form::checkbox('platform[]', $id, null, ['class' => 'form-check-input', 'id' => 'platform_'.$id]) !!}
                        {!! Form::label('platform_'.$id, $label, ['class' => 'form-check-label']) !!}
                     </div>
                  @endforeach
               </div>
               <div class="form-group form-group-default required mb-1">
                  {!! Form::label('data_access_level', 'Data Access Level', ['class' => 'control-label']) !!}
                  {!! Form::select('data_access_level', [
                      'all' => 'All regions',
                      'regional' => 'Regional Data',
                      'subregional' => 'Subregional ',
                      'route' => 'Routes Data',
                  ], null, [
                      'class' => 'form-control',
                      'required' => 'required',
                      'id' => 'data_access_level_select'
                  ]) !!}
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
               </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>

   {{--                  <div id="region_select" class="form-group form-group-default">--}}
   {{--                     {!! Form::label('region', 'Region', ['class' => 'control-label']) !!}--}}
   {{--                     {!! Form::select('region', $regions->pluck('name', 'id'), null, ['class' => 'form-control']) !!}--}}
   {{--                  </div>--}}

   {{--                  <div id="subregion_select" class="form-group form-group-default">--}}
   {{--                     {!! Form::label('subregion', 'Subregion', ['class' => 'control-label']) !!}--}}
   {{--                     {!! Form::select('subregion', $subregions->pluck('name', 'id'), null, ['class' => 'form-control']) !!}--}}
   {{--                  </div>--}}

   {{--                  <div id="area_select" class="form-group form-group-default">--}}
   {{--                     {!! Form::label('area', 'Area', ['class' => 'control-label']) !!}--}}
   {{--                     {!! Form::select('area', $areas->pluck('name', 'id'), null, ['class' => 'form-control']) !!}--}}
   {{--                  </div>--}}
   <script>
      $(document).ready(function() {
         // Hide subregion and area select inputs initially
         $('#region_select, #subregion_select, #area_select').hide();

         // Show/hide select inputs based on the selected data type option
         $('#data_type_select').change(function() {
            var selectedOption = $(this).val();

            if (selectedOption === 'region') {
               $('#region_select').show();
               $('#subregion_select, #area_select').hide();
            } else if (selectedOption === 'subregion') {
               $('#subregion_select').show();
               $('#region_select, #area_select').hide();
            } else if (selectedOption === 'route') {
               $('#area_select').show();
               $('#region_select, #subregion_select').hide();
            } else {
               $('#region_select, #subregion_select, #area_select').hide();
               $('#region_select, #subregion_select, #area_select').val('all');
            }
         });
      });
   </script>


@endsection
{{-- page scripts --}}
@section('scripts')

@endsection

