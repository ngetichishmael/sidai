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

                  <form method="post" action="{{ route('products.bulkUpdate', ['warehouse'=>$warehouse->warehouse_code]) }}" enctype="multipart/form-data">
                     @csrf

                     <div class="form-group">
                        <label for="excel_file">Excel File:</label>
                        <input type="file" name="excel_file" class="form-control-file" accept=".xls, .xlsx" required>
                     </div>

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
