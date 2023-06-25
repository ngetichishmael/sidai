@extends('layouts.app')
{{-- page header --}}
@section('title', 'Suppliers')

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-start mb-0">Suppliers | Reports</h2>
                </div>
            </div>
        </div>
    </div>
    @include('partials._messages')
    @livewire('supplier.index')

@endsection
{{-- page scripts --}}
@section('script')

@endsection
