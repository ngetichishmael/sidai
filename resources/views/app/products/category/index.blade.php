@extends('layouts.app')
{{-- page header --}}
@section('title','Item Category')
{{-- page styles --}}

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Categories</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Products</a></li>
                     <li class="breadcrumb-item active"><a href="#">Category</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>

   @include('partials._messages')
   <!-- begin card -->
   <div class="row">
      <div class="col-md-6">
         <div class="card card-inverse">
            <div class="card-body">
               <table id="data-table-default" class="table table-striped table-bordered">
                  <thead>
                     <tr>
                        <th width="1%">#</th>
                        <th width="20%">Name</th>
                        <th class="text-center" width="15.5%">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($category as $all)
                        <tr>
                           <td>{!! $count++ !!}</td>
                           <td>{!! $all->name !!}</td>

                           {{-- <td>{!! Finance::products_by_category_count($all->id) !!}</td> --}}
                           <td>
                              <div class="d-flex" style="gap:20px">
                                 <a href="{{ route('product.category.edit', $all->id) }}" class="btn btn-sm" style="background-color: #B6121B;color:white"><i class="far fa-edit"></i> Edit</a>
                                 <a href="{!! route('product.category.destroy', $all->id) !!}" class="btn btn-sm delete" style="background-color: #ee101b;color:white"><i class="fas fa-trash"></i> Delete</a>
                              </div>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="card card-default">
            <div class="card-body">
               <h4 class="card-title">Add Category</h4>
               {!! Form::open(array('route' => 'product.category.store')) !!}
                  @csrf
                  <div class="form-group form-group-default required">
                     {!! Form::label('name', 'Name', array('class'=>'control-label')) !!}
                     {!! Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter Category Name','required' => '')) !!}
                  </div>

                  <div class="form-group mt-4">
                     <center>
                        <button type="submit" class="btn submit" style="background-color: #B6121B;color:white"><i class="fas fa-save"></i> Add Category</button>
                        <img src="{!! asset('assets/img/btn-loader.gif') !!}" class="submit-load none" alt="" width="25%">
                     </center>
                  </div>
               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
