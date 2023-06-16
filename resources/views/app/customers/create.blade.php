@extends('layouts.app')
{{-- page header --}}
@section('title', 'New Customer')
{{-- page styles --}}


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->
    <div class="mb-2 row">
        <div class="col-md-8">
            <h2 class="page-header"><i data-feather="users"></i> Customers </h2>
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
                        <form class="form" method="POST" action="{{ route('customer.store') }}">
                            @method('POST')
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Customer Names</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Customer Name" name="customer_name" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">ID Number</label>
                                        <input type="number" maxlenth="9" id="first-name-column" class="form-control"
                                            placeholder="Id Number" name="id_number" />
                                    </div>
                                </div>
{{--                                <div class="col-md-6 col-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="last-name-column">Account</label>--}}
                                        <input type="hidden" id="last-name-column" class="form-control"
                                            placeholder="Account" name="account" value="Sidai" />
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6 col-12">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="city-column">Manufacturer Number</label>--}}
                                        <input type="hidden"  class="form-control"
                                            name="manufacturer_number" value="000000" />

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="address-id-column">Address</label>
                                        <input type="text" id="address" class="form-control" name="address"
                                            placeholder="address" />
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                        <input type="hidden" id="first-name-column" class="form-control"
                                            name="city" value="Nairobi" />

                                        <input type="hidden" id="country-floating" class="form-control" name="country"
                                            placeholder="Country" value="Kenya" />


                            </div>
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <label>Pricing category</label>
                                    <select class="select select2" wire:model='region' class="form-control"
                                        name="pricing_category">
                                        <option value="">Pricing category</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->outlet_name }}">{{ $group->outlet_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                        <input type="hidden" id="first-name-column" class="form-control"
                                            placeholder="Branch" name="branch" value="Sidai" />

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="last-name-column">Email</label>
                                        <input type="email" id="last-name-column" class="form-control"
                                            placeholder="Email" name="email" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="phone-column">Phone Number</label>
                                        <input type="number" id="phone_number" class="form-control"
                                            placeholder="Phone Number" name="phone_number">
                                    </div>
                                </div>
                                @livewire('customers.region')
                            </div>
                            <div class="my-1 col-sm-9 offset-sm-3">
                                <button type="submit" class="mr-1 btn btn-primary">Submit</button>
                                <a href="{{ route('customer') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
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
