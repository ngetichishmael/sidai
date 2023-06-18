@extends('layouts.app')
{{-- page header --}}
@section('title','Admins')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Admin | Details</h2>
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
                       <th>User Name</th>
                       <th>Region</th>
                       <th>Subregion</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($admins as $key=>$admin)
                  <tr>
                     <td>{{ $key+1 }}</td>
                     <td>{{ $admin->name }}</td>
                     <td>{{ $admin->Region->name??'' }}</td>
                     <td>{{ $admin->Subregion->name??'' }}</td>
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

