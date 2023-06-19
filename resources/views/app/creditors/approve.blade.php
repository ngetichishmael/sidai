@extends('layouts.app')
{{-- page header --}}
@section('title', 'Creditor Approval List')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="row mb-2">
        <div class="col-md-8">
            <h2 class="page-header">List of Creditors Waiting Approval</h2>
        </div>
{{--        <div class="col-md-4">--}}
{{--            <center>--}}
{{--                    <a href="{!! route('creditor.create') !!}" class="btn btn-success btn-sm"><i class="fa fa-user-plus"></i> Add a--}}
{{--                        Creditor</a>--}}
{{--                --}}{{-- <a href="{!! route('customer.export','csv') !!}" class="btn btn-warning btn-sm"><i class="fal fa-file-download"></i> Export Customer</a> --}}
{{--            </center>--}}
{{--        </div>--}}
    </div>
    @livewire('creditors.approve')
@endsection
@section('script')

@endsection
