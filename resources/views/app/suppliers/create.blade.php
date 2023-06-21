@extends('layouts.app')
{{-- page header --}}
@section('title','Add Distributor')
{{-- page styles --}}
@section('stylesheet')
<script type="text/javascript">
	.nav > li {
		position: relative;
		display: block;
		/* width: 100%; */
	}
</script>
@endsection

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Distributors</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="{{route('supplier')}}">Distributors</a></li>
                     <li class="breadcrumb-item active">Create</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
	{!! Form::open(array('route' => 'supplier.store','enctype'=>'multipart/form-data','method'=>'post' )) !!}
		{!! csrf_field() !!}
		<div class="card card-default">
         <div class="card-body row">
            <div class="form-group col-md-6 mt-1">
               {!! Form::label('Supplier', 'Distributor Name', array('class'=>'control-label')) !!}
               {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Distributor Name')) !!}
            </div>
            <div class="form-group col-md-6 mt-1">
               {!! Form::label('Email', 'Email', array('class'=>'control-label')) !!}
               {!! Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Enter Email')) !!}
            </div>
            <div class="form-group col-md-6 mt-1">
               {!! Form::label('Supplier', 'Phone number', array('class'=>'control-label')) !!}
               {!! Form::text('phone_number', null, array('class' => 'form-control', 'placeholder' => 'Enter Phone number')) !!}
            </div>
            <div class="form-group col-md-6 mt-1">
               {!! Form::label('telephone', 'Telephone', array('class'=>'control-label')) !!}
               {!! Form::text('telephone', null, array('class' => 'form-control', 'placeholder' => 'Enter telephone')) !!}
            </div>
            <div class="form-group col-md-6 mt-1">
               {!! Form::label('category', 'Category', array('class'=>'control-label')) !!}
               {!! Form::text('category', null, array('class' => 'form-control', 'placeholder' => '')) !!}
            </div>
            <div class="col-md-12 mt-2">
               <center>
                  <button type="submit" class="btn btn-success submit"><i class="fas fa-save"></i> Save Information</button>
               </center>
            </div>
         </div>
      </div>
	{!! Form::close() !!}
@endsection

