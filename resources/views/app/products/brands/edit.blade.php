@extends('layouts.app')
{{-- page header --}}
@section('title','Update Brand ')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Brands</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Brands</a></li>
                     <li class="breadcrumb-item active"><a href="#">Edit</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
   @livewire('brands.edit')
      <div class="col-md-6">
         <div class="card card-default">
            <div class="card-body">
               <h4 class="card-title">Update Brand</h4>
               {!! Form::model($brand, ['route' => ['product.brand.update',$brand->id], 'method'=>'post','enctype'=>'multipart/form-data','data-parsley-validate' => '']) !!}
                  @csrf
                  <div class="form-group form-group-default required">
                     {!! Form::label('name', 'Name', array('class'=>'control-label')) !!}
                     {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter Brand Name','required' => '')) !!}
                  </div>
                  <div class="form-group mt-4">
                     <center>
                        <button type="submit" class="btn submit" style="background-color: #B6121B;color:white"><i class="fas fa-save"></i> Update brand</button>
                     </center>
                  </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
