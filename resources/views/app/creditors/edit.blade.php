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
                    <h2 class="mb-0 content-header-title float-start">Creditor | Edit</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Creditor</a></li>
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
                c <div class="card">
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
                                        <label for="email-id-column">Address</label>
                                        <input type="text" id="email-id-column" class="form-control" name="address"
                                            placeholder="address" value="{{ $customer->address }}" />
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                {{-- @dd($customer) --}}
                                <div class="col-md-6 col-12">
                                    <label>Pricing Category</label>
                                    <select wire:model='region' class="form-control" name="pricing_category">
                                        <option value="">Pricing Category</option>

                                        @foreach ($groups as $group)
                                            <option value="{{ $group->outlet_name }}"
                                                @if ($group->outlet_name == $customer->customer_group) selected @endif>
                                                {{ $group->outlet_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-6 col-12">
                                    <label>Pricing Group</label>
                                    <select wire:model='region' class="form-control" name="price_group">
                                        <option value="">Pricing Group</option>
                                        @foreach ($pricing as $group)
                                            <option value="{{ $group->name }}"
                                                @if ($group->name == $customer->price_group) selected @endif>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>

                            <div class="row">
                                {{-- <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="first-name-column">Branch</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Branch" name="branch" value="{{ $customer->branch }}" />
                                    </div>
                                </div> --}}
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
                                <div class="col-md-6 col-12">
                                    <label>Zone</label>
                                    <select wire:model='region' class="form-control" name="zone">
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
                                    <select wire:model='regions'class="form-control" name="region">
                                        <option value="">Region</option>
                                        @foreach ($subregions as $subregion)
                                            <option value="{{ $subregion->id }}"
                                                @if ($subregion->id == $customer->subregion_id) selected @endif>{{ $subregion->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label>Route</label>
                                    <select class="form-control" name="territory">
                                        <option value="">Route</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}"
                                                @if ($area->id == $customer->zone_id) selected @endif>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- @livewire('customers.region') --}}
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

@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
