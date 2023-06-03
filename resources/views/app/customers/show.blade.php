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
                                        <h4>Customers Name:</h4>
                                        <p style="color:black">{{ $customer->customer_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Account:</h4>
                                        <p style="color:black">{{ $customer->account }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Manufacturer Number:</h4>
                                        <p style="color:black">{{ $customer->manufacturer_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>VAT Number:</h4>
                                        <p style="color:black">{{ $customer->vat_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Approved</h4>
                                        <p style="color:black">{{ $customer->approval ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Address:</h4>
                                        <p style="color:black">{{ $customer->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>City:</h4>
                                        <p style="color:black">{{ $customer->city ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Postal Code:</h4>
                                        <p style="color:black">{{ $customer->postal_code ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Customer Group:</h4>
                                        <p style="color:black">{{ $customer->customer_group ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Secondary Group:</h4>
                                        <p style="color:black">{{ $customer->customer_secondary_group ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Starus:</h4>
                                        <p style="color:black">{{ $customer->status ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Email:</h4>
                                        <p style="color:black">{{ $customer->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Phone:</h4>
                                        <p style="color:black">{{ $customer->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <h4>Created At:</h4>
                                        <p style="color:black">{{ $customer->created_at->format('jS F Y') ?? 'N/A' }}</p>
                                    </div>
                                </div>


                            </div>
                            
                            <div class="my-1 col-sm-9 offset-sm-3">
                                <a href="{{ route('creditor.approve', $customer->id) }}" class="btn btn-success">Approve To Creditor</a>
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