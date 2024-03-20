@extends('layouts.app')
{{-- page header --}}
@section('title','Visit Report Forms')

{{-- content section --}}
@section('content')
   <div class="row mb-2">
      <div class="col-md-8">
         <h2 class="page-header">Visit Report Forms</h2>
      </div>
   </div>
{{--   @include('partials._messages')--}}
<div class="row">
   <div class="col-8">
   @livewire('visit-report.index')
   </div>
<div class="col-4">
   <div class="container">
      <h1>Form Builder</h1>

      <form id="form-builder-form" method="POST" action="{{ route('forms.store') }}">
         @csrf

         <div class="form-group mt-1">
            <label for="name">Form Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
         </div>

         <div class="form-group mt-1">
            <label for="description">Form Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
         </div>
         <div class="form-group mt-1">
            <label for="description">Form Type</label>
            <select name="type" class="form-control" required>
               <option value=""> -- Select type --</option>
               <option value="checkout">Checkout Form</option>
               <option value="normal">Normal Form</option>
            </select>
         </div>

         <div id="form-fields" class="mt-1">
            <!-- Dynamic form fields will be rendered here -->
         </div>

         <button type="button" class="btn btn-primary mt-1" id="add-field-btn">Add Field</button>
         <button type="submit" class="btn btn-success mt-1">Save Form</button>
      </form>
   </div>
</div>
@endsection

   @section('scripts')
      <script src="{{ asset('/js/form-builder.js') }}"></script>
   @endsection
