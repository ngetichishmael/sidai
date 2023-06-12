@extends('layouts.app')
{{-- page header --}}
@section('title','Edit Warehouse')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0">Edit Warehouse </h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="#">Home</a></li>
                     <li class="breadcrumb-item"><a href="#">Warehouse</a></li>
                     <li class="breadcrumb-item active">Edit</li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
   @include('partials._messages')
   <div class="row">
      <div class="col-md-8">
         <div class="card">
            <div class="card-body ml-5">

               <h2>Warehouse Details</h2>
               <p><strong>Region:</strong> {{ $warehouse->region->name ?? '' }}</p>
               <p><strong>Subregion:</strong> {{ $warehouse->subregion->name ?? '' }}</p>
               <p><strong>Created on:</strong> {{ $warehouse->created_at ?? '' }}</p>
            </div>
         </div>

         <div class="card mt-8">
            <div class="card-header">
               <h3>Shop Attendees</h3>
            </div>
            <div class="card-body">
               <table class="table table-striped">
                  <thead>
                  <tr>
                     <th>Name</th>
                     <th>Role</th>
                     <th>Assigned On</th>
                     <th>Assigned By</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($attendees as $attendee)
                     <tr>
                        <td>{{ $attendee->manager->name ?? '' }}</td>
                        <td>{{ $attendee->position ?? ''}}</td>
                        <td>{{ $attendee->created_at ?? '' }}</td>
                        <td>{{ $attendee->user->name ?? '' }}</td>
                     </tr>
                  @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
            </div>
   <center>
      <a href="{{ url()->previous() }}" class="btn btn-success mt-2">
         <i data-feather='arrow-left'></i> Back
      </a>
   </center>
         </div>
      </div>
   </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
