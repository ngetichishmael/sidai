@extends('layouts.app')
{{-- page header --}}
@section('title', 'Chat')

{{-- content section --}}
@section('content')
   <!-- begin breadcrumb -->
   <div class="mb-2 row">
      <div class="col-md-9">
         <h2 class="page-header"> Messages </h2>
      </div>
      <div class="col-md-3">
      </div>
   </div>
   <!-- end breadcrumb -->
   @livewire('support.index')
@endsection
{{-- page scripts --}}
<script src="{{ mix('js/app.js') }}"></script>
@livewireScripts
@section('script')

@endsection
