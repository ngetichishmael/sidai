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
    </div>
    @livewire('creditors.approve')
@endsection
@section('script')

@endsection
