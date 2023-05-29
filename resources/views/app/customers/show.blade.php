@extends('layouts.app')
{{-- page header --}}
@section('title', 'Customer Details')
{{-- page styles --}}


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="mb-2 row">
        <div class="col-md-8">
            <h2 class="page-header"><i data-feather="users"></i>Customers | Details </h2>
        </div>
        <div class="col-md-4">


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
                        
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Customer Names</label>
                                        <p>{{ $customers->customer_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Account</label>
                                        <input type="text" id="last-name-column" class="form-control"
                                            placeholder="Account" name="account" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="city-column">Manufacturer Number</label>
                                        <input type="text" id="city-column" class="form-control" placeholder="City"
                                            name="manufacturer_number" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">VAT number</label>
                                        <input type="text" id="country-floating" class="form-control" name="vat_number"
                                            placeholder="VAT number" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="company-column">Delivery Time</label>
                                        <input type="text" id="company-column" class="form-control" name="delivery_time"
                                            placeholder="Delivery Time" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email-id-column">Address</label>
                                        <input type="text" id="email-id-column" class="form-control" name="address"
                                            placeholder="address" />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">City</label>
                                        <input type="text" id="first-name-column" class="form-control" placeholder="City"
                                            name="city" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Province</label>
                                        <input type="text" id="last-name-column" class="form-control"
                                            placeholder="Province" name="province" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="city-column">Postal Code</label>
                                        <input type="text" id="city-column" class="form-control"
                                            placeholder="Postal Code" name="postal_code" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country-floating">Country</label>
                                        <input type="text" id="country-floating" class="form-control" name="country"
                                            placeholder="Country" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="company-column">Latitude</label>
                                        <input type="text" id="company-column" class="form-control" name="latitude"
                                            placeholder="Latitude" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email-id-column">Longitude</label>
                                        <input type="email" id="email-id-column" class="form-control" name="longitude"
                                            placeholder="Longitude" />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Contact Person</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Contact Person" name="contact_person" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Telephone</label>
                                        <input type="text" id="last-name-column" class="form-control"
                                            placeholder="Telephone" name="telephone" />
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Branch</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Branch" name="branch" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Email</label>
                                        <input type="email" id="last-name-column" class="form-control"
                                            placeholder="Email" name="email" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="city-column">Phone Number</label>
                                        <input type="text" id="city-column" class="form-control"
                                            placeholder="Phone Number" name="phone_number" />
                                    </div>
                                </div>
                            </div>
                            <div class="my-1 col-sm-9 offset-sm-3">
                                <button type="submit" class="mr-1 btn btn-primary">Submit</button>
                                <a href="{{ route('customer') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Basic Floating Label Form section end -->
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
