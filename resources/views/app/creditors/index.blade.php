@extends('layouts.app')
{{-- page header --}}
@section('title', 'Creditors')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="row mb-2">
        <div class="col-md-8">
            <h2 class="page-header">Creditors List</h2>
        </div>

    </div>
    <!-- end breadcrumb -->

    @livewire('creditors.dashboard')
    {{-- @livewire('customers.index') --}}
@endsection
{{-- page scripts --}}
@section('script')

@endsection
