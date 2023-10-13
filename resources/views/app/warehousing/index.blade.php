@extends('layouts.app')
{{-- page header --}}
@section('title', 'Warehousing List')

{{-- content section --}}
@section('content')
    <div class="mb-2 row">
        <div class="col-md-8">
            <h2 class="page-header"><i data-feather="list"></i> Warehouses </h2>
        </div>
{{--          @if(Auth::check() && (strcasecmp(Auth::user()->account_type, "Admin") === 0 || strcasecmp(Auth::user()->account_type, "NSM") === 0 || strcasecmp(Auth::user()->account_type, "RSM") === 0))--}}
       @if(Auth::check() && in_array(strtolower(Auth::user()->account_type), ["admin", "nsm", "rsm"]))
       <div class="row">
          <div class="col-md-6">
                <center>
                    <a href="{!! route('warehousing.create') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white">Add Warehouse</a>
                    <a href="{!! route('products.add-common-products') !!}" class="btn btn-sm" style="background-color: #B6121B;color:white" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add similar product information in all warehouses">Add Common Products</a>
                </center>
            </div>
       </div>
           @endif
        </div>
        @include('partials._messages')
        @livewire('warehousing.index')
    @endsection
