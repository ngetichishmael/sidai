@extends('layouts.app')
{{-- page header --}}
@section('title', 'Import Products')
{{-- dashboad menu --}}

{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    {{-- <div class="pull-right">
      <a href="{!! route('products.sample.download') !!}" class="btn btn-pink"><i class="fas fa-file-download"></i> Download Sample</a>
   </div> --}}
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h3 class="page-header">Import Products</h3>
    @include('partials._messages')
    {{-- <div class="card card-default">
      <div class="card-heading">
         <h4 class="card-title">Upload Details</h4>
      </div>
      <div class="card-body">
         <div class="row">
            <ul>
               <li class="">Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is <b>UTF-8</b> to avoid unnecessary <b>encoding problems</b>.</li>
               <li class="">If the column <b>you are trying to import is date make sure that is formatted in format Y-m-d (2019-07-05).</b></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="card card-default">
      <div class="card-heading">
         <h4 class="card-title">CSV Format </h4>
      </div>
      <div class="card-body">
         <div class="table-responsive no-dt">
            <table class="table table-hover table-bordered">
               <thead>
                  <th>Item name</th>
                  <th>serial number</th>
                  <th>Description</th>
                  <th>inventory</th>
                  <th>buying_price</th>
                  <th>selling_price</th>
               </thead>
               <tbody>
                  <tr>
                     <td>Sample data</td>
                     <td>Sample data</td>
                     <td>Sample data</td>
                     <td>Sample data</td>
                     <td>Sample data</td>
                     <td>Sample data</td>
                  </tr>
               </tbody>
            </table>
         </div>
      </div>
   </div> --}}
    <div class="card card-default">
        <div class="card-body">
            <h4 class="card-title">Upload Items</h4>
            <div class="row">
                <div class="col-md-4 mtop15">
                    <form action="{!! route('products.post.imports') !!}" id="import_form" enctype="multipart/form-data" method="post"
                        accept-charset="utf-8">
                        @csrf
                        <input type="hidden" name="clients_import" value="true">
                        <div class="mb-2 form-group form-group-default">
                            <label for="file_csv" class="control-label text-danger">
                                <small class="req text-danger">*</small>Choose CSV File
                            </label>
                            <input type="file" name="upload_import"
                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success submit btn-sm">Import</button>
                            <img src="{!! asset('assets/img/btn-loader.gif') !!}" class="submit-load none" alt="" width="25%">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
