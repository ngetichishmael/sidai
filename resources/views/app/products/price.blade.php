@extends('layouts.app')
{{-- page header --}}
@section('title','Product Price')


{{-- content section --}}
@section('content')
<div class="content-header row">
   <div class="content-header-left col-md-12 col-12 mb-2">
      <div class="row breadcrumbs-top">
         <div class="col-12">
            <h2 class="content-header-title float-start mb-0">Products | {!! $product->product_name !!}</h2>
            <div class="breadcrumb-wrapper">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item"><a href="#">Products</a></li>
                  <li class="breadcrumb-item active">Price</li>
               </ol>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row">
   @include('app.products._product_menu')
   <div class="col-md-9">
      @if($product->same_price != 'No')
         {!! Form::model($defaultPrice,['route' =>['product.price.update',$defaultPrice->id],'method'=>'post','enctype'=>'multipart/form-data','data-parsley-validate' => '']) !!}
            <div class="card card-default">
               <div class="card-body">
                  <div class="col-md-12">
                     <h4 class="card-title">Product Price</h4>
                     <div class="form-group form-group-default required mb-2">
                        {!! Form::label('title', 'Buying Price Per Unit', array('class'=>'control-label')) !!}
                        {!! Form::number('buying_price', null, array('class' => 'form-control','step' => '0.01','placeholder' => 'Buying Price','required' => '')) !!}
                     </div>
                     <div class="form-group form-group-default required">
                        {!! Form::label('title', 'Selling Price Per Unit', array('class'=>'control-label')) !!}
                        {!! Form::number('selling_price', null, array('class' => 'form-control', 'step' => '0.01','placeholder' => 'Selling Price','required' => '')) !!}
                     </div>
                     <div class="form-group">
                        <center>
                           <button type="submit" class="btn btn-success submit mt-4"><i class="fas fa-save"></i> Update Price</button>
                           <img src="{!! asset('assets/img/btn-loader.gif') !!}" class="submit-load none" alt="" width="25%">
                        </center>
                     </div>
                  </div>
               </div>
            </div>
         {!! Form::close() !!}
      @endif

      @if($product->same_price == 'No')
         <div class="card card-default">
            <div class="card-heading">
               <h4 class="card-title mb-1">Prices</h4>
            </div>
            <div class="card-body">
               <div class="col-md-12 mt-3">
                  <div class="row">
                     <div class="col-md-12">
                        <table class="table table-striped">
                           <thead>
                              <th width="25%">Out Let</th>
                              <th>Buying price</th>
                              <th>Selling price</th>
                              <th>Offer price</th>
                              <th width="13%"></th>
                           </thead>
                           <tbody>
                              @foreach ($prices as $price)
                                 {!! Form::model($price,['route' =>['finance.price.update',$price->id],'method'=>'post']) !!}
                                    <tr>
                                       <td>
                                          @if($price->default_price == 'Yes')
                                             {!! $mainBranch->branch_name !!}
                                          @else
                                             @if(Hr::check_branch($price->branch_id) == 1)
                                                {!! Hr::branch($price->branch_id)->branch_name !!}
                                             @endif
                                          @endif
                                       </td>
                                       <td><input type="text" class="form-control" name="buying_price" value="{!! $price->buying_price !!}"></td>
                                       <td><input type="text" class="form-control" name="selling_price" value="{!! $price->selling_price !!}" required></td>
                                       <td><input type="text" class="form-control" name="offer_price" value="{!! $price->offer_price !!}"></td>
                                       <td>
                                          <button type="submit" class="btn btn-success btn-block"><i class="fas fa-edit"></i> Update Price</button>
                                       </td>
                                    </tr>
                                 {!! Form::close() !!}
                              @endforeach
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      @endif
   </div>
</div>
@endsection
{{-- page scripts --}}
