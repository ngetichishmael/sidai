@extends('layouts.app')
{{-- page header --}}
@section('title', 'Activity Logs')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
<div class="row mb-2">
    <div class="col-md-6">
        <h2 class="page-header">Activity Logs</h2>
    </div>
</div>
    <!-- Dashboard Ecommerce Starts -->
    @livewire('activity.dashboard')
    <!-- Dashboard Ecommerce ends -->
@endsection
{{-- page scripts --}}
@section('script')

@endsection

