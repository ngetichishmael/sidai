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
            <h2 class="page-header">Customers Waiting Approval</h2>
        </div>
    </div>
    @livewire('customers.approve')
@endsection
@section('script')

@endsection
