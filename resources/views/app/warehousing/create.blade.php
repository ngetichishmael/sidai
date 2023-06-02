@extends('layouts.app')
{{-- page header --}}
@section('title','Create Warehouse')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Add Warehouse </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Warehouse</a></li>
                     <li class="breadcrumb-item active">Create</li>
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
               <form action="{!! route('warehousing.store') !!}" method="POST">
                  @csrf
                  <div class="form-group mb-1">
                     <label for="">Warehouse Name</label>
                     {!! Form::text('name',null,['class'=>'form-control','required'=>'']) !!}
                  </div>
                  <div class="form-group mb-1">
                     <label for="">Choose Manager</label>
                     <select class="form-control select2" id="clerks" name="manager" required>
                             @foreach ($managers as $data)
                            <option value="{{ $data->user_code }}">{{ $data->name  }}</option>
                               @endforeach
                             </select>
                  </div>
                  <div class="form-group mb-1">
                     <label for="">Managers Email</label>
                     {!! Form::email('email',null,['class'=>'form-control']) !!}
                  </div>
                  <div class="form-group mb-1">
                     <label for="">Phone number</label>
                     {!! Form::text('phone_number',null,['class'=>'form-control']) !!}
                  </div>
                  <div>
                     @livewire('regionselect.dynamicselect')
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-check form-switch">
                           <label class="form-check-label" for="customSwitch1">Is main warehouse</label>
                           <input type="checkbox" class="form-check-input" name="is_main" id="customSwitch1" value="Yes" />
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-check form-switch">
                           <label class="form-check-label mb-50" for="customSwitch4">Is Active</label>
                           <input type="checkbox" class="form-check-input" name="status" id="customSwitch4" value="Active" />
                        </div>
                     </div>
                  </div>
                  <center><button class="btn btn-success mt-2" type="submit"><i data-feather='save'></i> Save Information</button></center>
               </form>
            </div>
         </div>
      </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
