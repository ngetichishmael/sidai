@extends('layouts.app')
{{-- page header --}}
@section('title', 'Activity Logs')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <!-- Dashboard Ecommerce Starts -->
    @livewire('activity.dashboard')
    <!-- Dashboard Ecommerce ends -->
@endsection
{{-- page scripts --}}
@section('script')

@endsection

