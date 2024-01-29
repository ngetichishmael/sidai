@extends('layouts.app')
{{-- page header --}}
@section('title', 'Product Prices Updates')


{{-- content section --}}
@section('content')
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-8">
            <div class="card">
               <div class="card-header">{{ __('Bulk Price Update') }}</div>

               <div class="card-body">
                  @if(session('success'))
                     <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                     </div>
                  @endif
                     <div class="alert alert-info" role="alert">
                        <strong>Instructions:</strong>
                        <ul>
                           <li>Prepare an Excel file with the following columns: <strong>product sku</strong>,<strong>distributor</strong>, <strong>wholesale</strong>, <strong>retail</strong>.</li>
                           <li>Ensure the file format is either <strong>.xls</strong> or <strong>.xlsx</strong>.</li>
                           <li>Fill in the values for each product accordingly.</li>
                           <li>Upload</li>
                        </ul>
                     </div>
                  <form method="post" action="{{ route('products.bulkUpdate', ['warehouse'=>$warehouse]) }}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group">
                        <label for="excel_file">Excel File:</label>
                        <input type="file" name="excel_file" class="form-control-file" accept=".xls, .xlsx" required>
                     </div>
                  <br/>
                     <button type="submit" class="btn btn-primary">Update Prices</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
{{-- page scripts --}}
@section('scripts')
@endsection
