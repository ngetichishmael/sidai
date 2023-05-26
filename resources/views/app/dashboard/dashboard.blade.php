@extends('layouts.app')
@section('title', 'Dashboard')
@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/plugins/charts/chart-apex.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/pages/dashboard-ecommerce.min.css') !!}">
@endsection
@section('content')
    @livewire('dashboard.dashboard')
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/js/charts/apexcharts.min.js') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/js/scripts/pages/dashboard-ecommerce.min.js') !!}">
@endsection
