@extends('layouts.app')
{{-- page header --}}
@section('title','Edit Warehouse')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Edit Warehouse </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="{{route('warehousing.index')}}">Warehouses</a></li>
                     <li class="breadcrumb-item active">Edit</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
      <div class="col-md-6">
         <div class="card">
            <div class="card-body">
               {!! Form::model($edit, ['route' => ['warehousing.update',$edit->warehouse_code], 'method'=>'post','enctype'=>'multipart/form-data']) !!}
                  @csrf
                  <div class="form-group mb-1">
                     <label for="">Name</label>
                     {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
                  </div>
               <div class="form-group mb-1">
                  <label for="region_id">Region:</label>
                  <select id="region_id" class="form-control select2" name="region_id" required>
                     <option value="">Select a region</option>
                     @foreach($regions as $region)
                        <option value="" selected>{{$edit->region->name ?? ''}}</option>
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="form-group mb-1">
                  <label for="subregion_id">Subregion:</label>
                  <select id="subregion_id" class="form-control select2" name="subregion_id">
                     <option value="" selected>{{$edit->subregion->name ?? ''}}</option>
                  </select>
               </div>
{{--                  <div class="row">--}}
{{--                     <div class="col-md-6">--}}
{{--                        <div class="form-group mb-1">--}}
{{--                           <label for="">Longitude</label>--}}
{{--                           {!! Form::text('longitude',null,['class'=>'form-control']) !!}--}}
{{--                        </div>--}}
{{--                     </div>--}}
{{--                     <div class="col-md-6">--}}
{{--                        <div class="form-group mb-1">--}}
{{--                           <label for="">Latitude</label>--}}
{{--                           {!! Form::text('latitude',null,['class'=>'form-control']) !!}--}}
{{--                        </div>--}}
{{--                     </div>--}}
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-check form-switch">
                           <label class="form-check-label" for="customSwitch1">Is main warehouse</label>
                           <input type="checkbox" class="form-check-input" name="is_main" id="customSwitch1" value="Yes" @if($edit->is_main == 'Yes') checked @endif />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-check form-switch">
                           <label class="form-check-label mb-50" for="customSwitch4">Is Active</label>
                           <input type="checkbox" class="form-check-input" name="status" id="customSwitch4" value="Active"  @if($edit->status == 'Active') checked @endif />
                        </div>
                     </div>
                  </div>
                  <center><button class="btn btn-success mt-2" type="submit"><i data-feather='save'></i> Save Information</button></center>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
      $(document).ready(function() {
         $('#region_id').change(function() {
            var regionId = $(this).val();
            if (regionId) {
               $.ajax({
                  url: "{{ route('get-subregions', '') }}/" + regionId,
                  type: "GET",
                  dataType: "json",
                  success: function(data) {
                     $('#subregion_id').empty();
                     $('#subregion_id').append('<option value="">Choose a Subregion</option>');
                     if (data.length > 0) {
                        $.each(data, function(key, value) {
                           $('#subregion_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                     }
                  }
               });
            } else {
               $('#subregion_id').empty();
            }
         });
      });
   </script>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
