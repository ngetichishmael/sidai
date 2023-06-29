@extends('layouts.app')
{{-- page header --}}
@section('title', 'Create Role')
{{-- page styles --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

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
                        <th>Created by</th>
                        <th>Updated by</th>
                        <th>Actions</th>
                     </tr>
                     </thead>
                     <tbody class="font-small-3">
                     @foreach ($roles as $key => $role)
                        <tr>
                           <td>{{ $role->name }}</td>
                           <td>{{ $role->display_name}}</td>
                           <td>{{ $role->access_to}}</td>
                           <td>{{ $role->checkedPlatforms() }}</td>
                           <td>{{ $role->CreatedBy->name ?? '' }}</td>
                           <td>{{ $role->UpdatedBy->name ?? '' }}</td>
                           <td><a href="#" class="btn btn-sm" style="background-color: #B6121B;color:white">Edit</a></td>
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
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('description', 'Description', ['class' => 'control-label']) !!}
                     {!! Form::text('description', null, [
                         'class' => 'form-control',
                         'placeholder' => 'Enter Role Description',
                     ]) !!}
                  </div>
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('platform', 'Platform', ['class' => 'control-label']) !!}
                     <br>
                     @foreach([
                         'Sales App' => 'Sales App',
                         'Managers App' => 'Managers App',
                         'Manager Dashboard' => 'Manager Dashboard',
                         'Shop Attendee Dashboard' => 'Shop Attendee Dashboard',
                         'Admin' => 'Admin',
                     ] as $value => $label)
                        <div class="form-check form-check-inline mt-0.9">
                           {!! Form::checkbox('platform[]', $value, null, ['class' => 'form-check-input', 'id' => 'platform_'.$value]) !!}
                           {!! Form::label('platform_'.$value, $label, ['class' => 'form-check-label']) !!}
                        </div>
                     @endforeach
                  </div>
                  <div class="form-group form-group-default required mb-1">
                     {!! Form::label('data_type', 'Type of data', ['class' => 'control-label']) !!}
                     {!! Form::select('data_type', [
                         'all' => 'All regions',
                         'region' => 'Regional Data',
                         'subregion' => 'Subregional ',
                         'route' => 'Routes Data',
                     ], null, [
                         'class' => 'form-control',
                         'required' => 'required',
                         'id' => 'data_type_select'
                     ]) !!}
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

