@extends('layouts.app')
{{-- page header --}}
@section('title', 'Supplier List')

{{-- content section --}}
@section('content')
    <div class="row mb-2">
        <div class="col-md-8">
            <h2 class="page-header"> Supplier</h2>
        </div>
        <div class="col-md-4">
            <center>
                <a href="{!! route('supplier.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white">Add Supplier</a>
                <a href="{{ route('supplier.import.index') }}" class="btn btn-sm" style="background-color: #B6121B;color:white">Import Suppliers</a>
                <a href="{{ route('supplier.archive.view') }}" class="btn btn-sm" style="background-color: #fd6b37;color:white">View Archive</a>
            </center>
        </div>
    </div>
    @include('partials._messages')
    @livewire('supplier.dashboard')
@endsection
{{-- page scripts --}}
@section('script')

@endsection
