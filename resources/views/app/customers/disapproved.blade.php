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
        <div class="col-md-10">
            <h2 class="page-header">Disapproved Customers List</h2>
        </div>
       <div class="col-md-2">
          <a href="/approveCustomers" class="btn btn-md btn-secondary">Back</a>
       </div>
    </div>
    @livewire('customers.disapproved')
@endsection
@section('script')

@endsection
