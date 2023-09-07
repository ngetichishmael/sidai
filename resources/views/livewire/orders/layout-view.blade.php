@extends('layouts.app')
{{-- page header --}}
@section('title', 'Leads Target')

{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="row mb-2">
        <div class="col-md-8">
            <h3 class="page-header"><i data-feather="list"></i> History Orders Target for {{ $name }} </h3>
        </div>
    </div>
    <!-- end breadcrumb -->
    <!-- begin page-header -->

    <!-- end page-header -->
    @include('partials._messages')
    {{-- @livewire('products.products') --}}
    @livewire('orders.dashboard-view', [
        'user_code' => $user_code,
    ])

@endsection
