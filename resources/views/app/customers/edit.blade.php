@extends('layouts.app')
{{-- page header --}}
@section('title', 'Edit Customer')
{{-- page styles --}}

{{-- content section --}}
@section('content')
    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Customer | Edit</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Customer</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-8">
                 <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Customers</h4>
                    </div>
                    <div class="card-body">
                        <form class="form" method="POST"
                            action="{{ route('customer.update', ['customer' => $customer]) }}">

                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Customer Names</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Customer Name" name="customer_name"
                                            value="{{ $customer->customer_name }}" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Contact Person</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            value="{{ $customer->contact_person }}" name="contact_person" />
                                    </div>
                                </div>
                               
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email-id-column">Address</label>
                                        <input type="text" id="email-id-column" class="form-control" name="address"
                                            placeholder="address" value="{{ $customer->address }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label>Customer Group</label>
                                    <select class="form-control" name="customer_group">
                                        <option value="">Customer Group</option>

                                        @foreach ($groups as $group)
                                            <option value="{{ $group->group_name }}"
                                                @if ($group->group_name == $customer->customer_group) selected @endif>
                                                {{ $group->group_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label>Pricing Category</label>
                                    <select class="form-control" name="pricing_category">
                                        <option value="">Pricing Category</option>

                                        @foreach ($prices as $price)
                                            <option value="{{ $price->name }}"
                                                @if ($price->name == $price->name) selected @endif>
                                                {{ $price->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Email</label>
                                        <input type="email" id="last-name-column" class="form-control"
                                            placeholder="Email" name="email" value="{{ $customer->email }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="city-column">Phone Number</label>
                                        <input type="text" id="city-column" class="form-control"
                                            placeholder="Phone Number" name="phone_number"
                                            value="{{ $customer->phone_number }}" />
                                    </div>
                                </div>
                                {{-- @livewire('customers.regionedit') --}}
                                <div class="col-md-6 col-12">
                                    <label>Region</label>
                                    <select class="form-control" name="zone" id="regionSelect">
                                        <option value="">Region</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}"
                                                @if ($region->id == $customer->region_id) selected @endif>
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-12">
                                    <label>Sub Region</label>
                                    <select class="form-control select2" name="region" id="subRegionSelect">
                                        <option value="">Region</option>
                                        
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label>Route</label>
                                    <select class="form-control select2" name="route" id="routeSelect">
                                        <option value="">Route</option>
                                        
                                    </select>
                                </div>

                            </div>
                               
                            <div class="my-1 col-sm-9 offset-sm-3">
                                <button type="submit" class="mr-1 btn" style="background-color: #B6121B;color:white">Update</button>
                                <a href="{{ route('customer') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Floating Label Form section end -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
       $(document).ready(function () {
        $('#regionSelect').on('change', function () {
            var regionId = $(this).val();
    
            // Make an AJAX request to fetch subregions based on the selected region
            $.ajax({
                url: '/get-subregions/' + regionId,
                type: 'GET',
                success: function (data) {
                    // Populate the subregion dropdown with new options
                    $('#subRegionSelect').html(data);
                }
            });
        });
    
        // Sub Region dropdown change event
        $('#subRegionSelect').on('change', function () {
            var subRegionId = $(this).val();
    
            // Make an AJAX request to fetch routes based on the selected subregion
            $.ajax({
                url: '/get-routes/' + subRegionId,
                type: 'GET',
                success: function (data) {
                    // Populate the route dropdown with new options
                    $('#routeSelect').html(data);
                }
            });
        });
    });
    </script>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
