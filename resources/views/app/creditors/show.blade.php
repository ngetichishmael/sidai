@extends('layouts.app')
{{-- page header --}}
@section('title', 'Creditor Details')
{{-- page styles --}}


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    @livewire('creditors.view', [
        'customer_id' => $id,
    ])
   
    <!-- Basic Floating Label Form section end -->
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
