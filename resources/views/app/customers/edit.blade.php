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
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="form-group">
                                        <label for="first-name-column">Customer Names</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            placeholder="Customer Name" name="customer_name"
                                            value="{{ $customer->customer_name }}" />
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-2">
                                    <div class="form-group">
                                        <label for="first-name-column">Contact Person</label>
                                        <input type="text" id="first-name-column" class="form-control"
                                            value="{{ $customer->contact_person }}" name="contact_person" />
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 mb-2">
                                    <div class="form-group">
                                        <label for="email-id-column">Address</label>
                                        <input type="text" id="email-id-column" class="form-control" name="address"
                                            placeholder="address" value="{{ $customer->address }}" readonly />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
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
                                <div class="col-md-6 col-12 mb-2">
                                    <label>Pricing Category</label>
                                    <select class="form-control" name="pricing_category">
                                        <option value="">Pricing Category</option>

                                        @foreach ($prices as $price)
                                            <option value="{{ $price->name }}"
                                                @if ($price->name == $customer->price_group) selected @endif>
                                                {{ $price->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="form-group">
                                        <label for="last-name-column">Email</label>
                                        <input type="email" id="last-name-column" class="form-control" placeholder="Email"
                                            name="email" value="{{ $customer->email }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mb-2">
                                    <div class="form-group">
                                        <label for="city-column">Phone Number</label>
                                        <input type="text" id="city-column" class="form-control"
                                            placeholder="Phone Number" name="phone_number"
                                            value="{{ $customer->phone_number }}" />
                                    </div>
                                </div>
                                {{-- @livewire('customers.region') --}}
                                <div class="col-md-6 col-12 mb-2">
                                    <label>Region :  <span style="color: #d5512d">{{$customer->region->name ?? ''}}</label>
                                    <select id="regionId" class="form-control" name="zone">
                                        <option value="">Region</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}"
                                                @if ($region->id == $customer->region_id) selected @endif>
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
{{--                               @dd($customer->subregion->name)--}}
                               <div class="col-md-6 col-12 mb-2">
                                  <label>Sub Region :  <span style="color: #d5512d">{{$customer->subregion->name ?? ''}}</span></label>
                                  <select id="subregionId" class="form-control" name="region">
                                     <option value="">{{$customer->subregion->name ?? 'Subregion'}}</option>
                                     @foreach ($subregions as $subregion)
                                        <option value="{{ $subregion->id }}"
                                   @if ($subregion->id == $customer->subregion_id) selected @endif>
                                           {{ $subregion->name }}
                                        </option>
                                     @endforeach
                                  </select>
                               </div>
                               <div class="col-md-6 col-12 mb-2">
                                  <label>Route/Town:    <span style="color: #d5512d"> {{$customer->Area->name ?? ''}}</span></label>
                                  <select id="areaId" class="form-control" name="territory">
                                     <option value="">{{$customer->Area->name ?? 'Route(Town)'}}</option>
                                     @foreach ($areas as $route)
                                        <option value="{{ $route->id }}"
                                  @if ($route->id == $customer->route_id) selected @endif>
                                           {{ $route->name }}
                                        </option>
                                     @endforeach
                                  </select>
                               </div>

{{--                                <div class="col-md-6 col-12 mb-2">--}}
{{--                                    <label>Sub Region</label>--}}
{{--                                    <select id="subregionId" class="form-control" name="region">--}}

{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6 col-12 mb-2">--}}
{{--                                    <label>Route</label>--}}
{{--                                    <select id="areaId" class="form-control" name="territory">--}}
{{--                                    </select>--}}
{{--                                </div>--}}

                            </div>
                           <input type="hidden" value="customer" name="in">
                            <div class="my-1 col-sm-9 offset-sm-3">
                                <button type="submit" class="mr-1 btn"
                                    style="background-color: #B6121B;color:white">Update</button>
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
        const baseUrl = window.location.origin;

        function populateSubregions(regionId) {
            const subregionSelect = document.getElementById('subregionId');
            subregionSelect.innerHTML = '<option value="">Subregion</option>';
            if (!regionId) {
                return;
            }
            const fetchUrl = `${baseUrl}/api/get/subregion/${regionId}`;
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                    } else {
                        data.data.forEach(subregion => {
                            const option = document.createElement('option');
                            option.value = subregion.id;
                            option.textContent = subregion.name;
                            subregionSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error when fetching subregions:', error);
                });
        }

        function populateAreas(subregionId) {
            const areaSelect = document.getElementById('areaId');

            areaSelect.innerHTML = '<option value="">Route</option>';

            if (!subregionId) {
                return;
            }


            const fetchUrl = `${baseUrl}/api/get/area/${subregionId}`;

            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                    } else {
                        data.data.forEach(area => {
                            const option = document.createElement('option');
                            option.value = area.id;
                            option.textContent = area.name;
                            areaSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error when fetching subregions:', error);
                });
        }

        // Trigger initial population of subregions
        document.addEventListener('DOMContentLoaded', function() {
            const initialRegionId = document.getElementById('regionId').value;
            populateSubregions(initialRegionId);
        });

        // Listen for region selection changes
        document.getElementById('regionId').addEventListener('change', function() {
            const selectedRegionId = this.value;
            populateSubregions(selectedRegionId);
        });
        // Trigger initial population of subregions
        document.addEventListener('DOMContentLoaded', function() {
            const initialSubRegionId = document.getElementById('subregionId').value;
            populateAreas(initialRegionId);
        });

        // Listen for region selection changes
        document.getElementById('subregionId').addEventListener('change', function() {
            const selectedSubRegionId = this.value;
            populateAreas(selectedSubRegionId);
        });
    </script>
@endsection
{{-- page scripts --}}
@section('scripts')

@endsection
