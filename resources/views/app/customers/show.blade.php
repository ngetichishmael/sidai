@extends('layouts.app')
{{-- page header --}}
@section('title', 'Customer Details')
{{-- page styles --}}


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    @livewire('customers.view', [
        'customer_id' => $id,
    ])
   
    <!-- Basic Floating Label Form section end -->
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
