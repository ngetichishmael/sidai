@extends('layouts.app')
{{-- page header --}}
@section('title', 'Customer')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="row mb-2">
        <div class="col-md-6">
            <h2 class="page-header">Customers List</h2>
        </div>
        <div class="col-md-6">
            <center>
{{--                <a href="{!! route('customer.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white"><i data-feather="user-plus"></i> Add a--}}
{{--                    Customer</a>--}}
{{--                    <a href="{!! route('creditor.create') !!}" class="btn btn-success btn-sm"><i class="fa fa-user-plus"></i> Add a--}}
{{--                        Creditor</a>--}}
                <a href="{{ route('user-import') }}" class="btn btn-sm" style="background-color: #b8282f;color:white"><i data-feather="download"></i> Import
                    Customer</a>
                {{-- <a href="{!! route('customer.export','csv') !!}" class="btn btn-warning btn-sm"><i class="fal fa-file-download"></i> Export Customer</a> --}}
            </center>
        </div>
    </div>
    <!-- end breadcrumb -->
    @livewire('customers.dashboard')
    {{-- @livewire('customers.index') --}}
@endsection
{{-- page scripts --}}
@section('script')

@endsection
