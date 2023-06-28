<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
</div>
@extends('layouts.app')
{{-- page header --}}
@section('title', 'Customer Groups')
{{-- page styles --}}
@php
    use Illuminate\Support\Str;
@endphp
{{-- content section --}}
@section('content')
    @include('partials._messages')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Customer Groups</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Customer Groups</a></li>
                            <li class="breadcrumb-item"><a href="#">All</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- begin card -->
    <div class="row">
        @livewire('customer-group.customergroup')
        <div class="col-md-6">
            <div class="card card-default">
                <div class="card-body">
                    <div class="card-body">
                        <h4 class="card-title">Add Customer Group</h4>

                        <form class="form" method="POST" action="{{ route('groupstore') }}">
                            @csrf
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="group_name">Group Name</label>
                                    <input type="text" id="group_name" class="form-control" placeholder="Group Name"
                                        name="group_name" required />
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <input type="text" id="business_code" class="form-control"
                                        value="{{ Auth::user()->business_code }}" name="business_code" hidden readonly />
                                </div>
                            </div>

                            <div class="my-1 col-sm-9 offset-sm-3">
                                <button type="submit" class="mr-1 btn " style="background-color: #B6121B;color:white">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
