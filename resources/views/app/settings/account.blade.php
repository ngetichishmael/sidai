@extends('layouts.app')
@section('title', 'Account Settings')
@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/plugins/charts/chart-apex.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/css/pages/dashboard-ecommerce.min.css') !!}">
@endsection
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Account Details</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Account</a></li>
                            <li class="breadcrumb-item active">Account Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('app.settings._menu')

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {!! Form::model($account, [
                        'route' => ['settings.account.update', Auth::user()->id],
                        'class' => 'row',
                        'method' => 'post',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    {!! csrf_field() !!}
                    <div class="form-group mb-1 col-md-6">
                        <label for="">Business Name</label>
                        {!! Form::text('name', null, ['class' => 'form-control', 'required' => '']) !!}
                    </div>
                    <div class="form-group mb-1 col-md-6">
                        <label for="">Email</label>
                        {!! Form::text('email', null, ['class' => 'form-control', 'required' => '']) !!}
                    </div>
                    <div class="form-group mb-1 col-md-6">
                        <label for="">Location</label>
                        {!! Form::text('business_location', null, ['class' => 'form-control', 'required' => '']) !!}
                    </div>
                    <div class="form-group mb-1 col-md-6">
                        <label for="">Country</label>
                        {!! Form::select('country', $country, null, ['class' => 'form-control', 'required' => '']) !!}
                    </div>
                    <div class="form-group mb-1 col-md-6">
                        <label for="">Phone Number</label>
                        {!! Form::text('phone_number', null, ['class' => 'form-control', 'required' => '']) !!}
                    </div>
                    <div class="mb-1 col-md-12">
                        <center><button type="submit" class="btn" style="background-color: #B6121B;color:white">Save Information</button></center>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/vendors/js/charts/apexcharts.min.js') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('app-assets/js/scripts/pages/dashboard-ecommerce.min.js') !!}">
@endsection
