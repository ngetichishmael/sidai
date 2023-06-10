@extends('layouts.app')
{{-- page header --}}
@section('title','warehouse')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Warehouse | Reports</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     {{-- <li class="breadcrumb-item"><a href="#">Home</a></li> --}}
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
    <div class="col-md-12">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Warehouse Name</th>
                       <th>Shop Attendees</th>
                       <th>Region</th>
                       <th>Subregion</th>
                       <th>Quantity</th>
                       <th>Last Re-stock</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($warehouses as $warehouse)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $warehouse->name }}</td>
                     <td></td>
                     <td>{{ $warehouse->region->name??'' }}</td>
                     <td>{{ $warehouse->subregion->name??'' }}</td>
                     <td>{{ $warehouse->product_information_count }}</td>
                     <td></td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection

