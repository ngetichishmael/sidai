@extends('layouts.app')
{{-- page header --}}
@section('title', 'Route Scheduling')
{{-- page styles --}}

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Route Scheduling</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Route</a></li>
                            <li class="breadcrumb-item active">Scheduling</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    {!! Form::open([
                        'route' => 'routes.store',
                        'class' => 'row',
                        'enctype' => 'multipart/form-data',
                        'method' => 'post',
                    ]) !!}
                    {!! csrf_field() !!}
                    <div class="form-group col-md-12 mb-1">
                        @livewire('routes.customerselect')
                        {{-- <label for="">Routes</label>
                        {!! Form::select('name', $routes, null, ['class' => 'form-control select2']) !!} --}}
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-md-4">
                            <label for="">Start Date</label>
                            {!! Form::date('start_date', null, ['class' => 'form-control', 'required' => '']) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">End Date</label>
                            {!! Form::date('end_date', null, ['class' => 'form-control', 'required' => '']) !!}
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Status</label>
                            {!! Form::select('status', ['' => 'Choose status', 'Active' => 'Active', 'Close' => 'Close'], null, [
                                'class' => 'form-control',
                                'required' => '',
                            ]) !!}
                        </div>
                    </div>
                    {{-- <div class="form-group col-md-12 mb-1">
                        <label for="">Add Customer to Route</label>
                        {!! Form::select('customers[]', $customers, null, ['class' => 'form-control select2', 'multiple' => '']) !!}
                    </div> --}}
                    <div class="form-group col-md-12 mb-1">
                        <label for="">Add sales people to Route</label>
                        {!! Form::select('sales_persons[]', $salesPeople, null, ['class' => 'form-control select2', 'multiple' => '']) !!}
                    </div>
                    <div class="form-group mb-1">
                        <button class="btn" style="background-color: #B6121B;color:white" type="submit">Save Information</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
