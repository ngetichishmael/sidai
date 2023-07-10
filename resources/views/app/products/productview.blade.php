@extends('layouts.app')
{{-- page header --}}
@section('title', 'Product View')


{{-- content section --}}
@section('content')
    <!-- begin page-header -->
    <style>
       /* CSS styles */
       table {
          width: 100%;
          border-collapse: collapse;
       }

       th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd;
       }

       .sku-field input {
          width: 100%;
          padding: 8px;
          box-sizing: border-box;
       }

       .sku-field .remove-sku {
          color: #fff;
          background-color: #f44336;
          border: none;
          padding: 8px 12px;
          cursor: pointer;
       }

       .sku-field .remove-sku:hover {
          background-color: #d32f2f;
       }
    </style>
    <h3 class="page-header"> Update Price for <b>{{$product_information->product_name ?? ''}}</b></h3>
    <!-- end page-header -->
    <form class="needs-validation responsive mt-3 mb-3" action="{{ route('products.updatesingle', [
        'id' => $id]) }}" method="POST"
          enctype="multipart/form-data" id="restock-form">
       @csrf
       <table id="sku-table responsive">
          <thead>
          <tr>
             <th>Wholesale Price</th>
             <th>Distributor Price</th>
             <th>Retail Price </th>
          </tr>
          </thead>
          <tbody id="sku-fields">
          <tr class="sku-field ">
             <td><input for="fp-date-time" type="number" class="form-control col-lg-1 col-md-3" name="buying_price" value="{{$product_price->buying_price??0}}"  required></td>
             <td><input for="fp-date-time" type="number" class="form-control col-lg-1 col-md-3" name="distributor_price" value="{{$product_price->distributor_price ?? 0}}"></td>
             <td><input for="fp-date-time" type="number" class="form-control col-lg-1 col-md-3" name="selling_price" value="{{$product_price->selling_price?? 0}}"></td>
             
          </tr>
          </tbody>
       </table>

       <div class=" col-l-1 mt-3 pe-4 text-right">
          <button type="submit" class="btn btn-primary data-submit w-10">Update</button>
       </div>
    </form>

   
@endsection
{{-- page scripts --}}
@section('scripts')
@endsection
