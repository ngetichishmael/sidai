@extends('layouts.app')
{{-- page header --}}
@section('title', 'Create User')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <!-- begin breadcrumb -->

    <div class="content-header row">
        <div class="mb-2 content-header-left col-md-12 col-12">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="mb-0 content-header-title float-start">Users </h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/users-Roles">Roles List</a></li>
                            <li class="breadcrumb-item"><a href="{{ url()->previous()}}">Users</a></li>
                            <li class="breadcrumb-item active">Create</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{!! route('user.store') !!}" style="gap: 20px;">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-2 col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">Name</label>
                                                    <input type="text" id="first-name-column" class="form-control"
                                                        placeholder="name" name="name" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="last-name-column">Email</label>
                                                    <input type="email" id="last-name-column" class="form-control"
                                                        placeholder="Email" name="email" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="city-column">Phone Number</label>
                                                    <input type="tel" id="city-column" class="form-control"
                                                        pattern="[0789][0-9]{9}" placeholder="0700000000"
                                                        name="phone_number" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="select-country">User Role</label>
                                                    <select class="form-control select2" id="account-type"
                                                        name="account_type" required>
                                                        <option value="">Select Role</option>
                                                       @foreach ($roles as $value)
                                                          <option value="{{ $value->name }}">{{ $value->name }}-{{ $value->description }}
                                                          </option>
                                                       @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12 mt-1">
                                                <div class="form-group">
                                                    <label for="select-region">Regions</label>
                                                    <select class="form-control select2" id="region" name="region"
                                                        required>
                                                        <option value="">Select a Region</option>
                                                        @foreach ($regions as $value)
                                                            <option value="{{ $value->id }}">{{ $value->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <!-- Account Tab starts -->
                            <div class="tab-pane active" id="account" aria-labelledby="account-tab" role="tabpanel">
                                <form class="form-validate">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mt-1 border rounded table-responsive">
                                                <h6 class="py-1 mx-1 mb-0 font-medium-2">
                                                    <i data-feather="lock" class="font-medium-3 mr-25"></i>
                                                    <span class="align-middle">Permission Out Side Customer Shop</span>
                                                </h6>
                                                <table class="table table-striped table-borderless">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Module</th>
                                                            <th>Permission</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Van Sales</td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="admin-read" name="van_sales" checked />
                                                                    <label class="custom-control-label"
                                                                        for="admin-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>New Sales</td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="staff-read" name="new_sales" checked />
                                                                    <label class="custom-control-label"
                                                                        for="staff-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Deliveries</td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="author-read" name="deliveries" checked />
                                                                    <label class="custom-control-label"
                                                                        for="author-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Schedule Visits </td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="contributor-read" name="schedule_visits"
                                                                        checked />
                                                                    <label class="custom-control-label"
                                                                        for="contributor-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Merchanizing</td>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="user-read" name="merchanizing" checked />
                                                                    <label class="custom-control-label"
                                                                        for="user-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                       <div class="row">
                                          <di class="col-md-8 col-sm-5 "></di>
                                        <div class="mt-2  col-md-4 col-sm-5 d-flex flex-sm-row flex-column" style="gap: 20px;">
                                            <button type="submit" class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Create User</button>
                                            <a href="{{ url()->previous() }}" type="reset"
                                                class="btn btn-outline-secondary">Cancel</a>
                                        </div>
                                       </div>
                                    </div>
                                </form>
                                <!-- users edit account form ends -->
                            </div>
                            <!-- Account Tab ends -->
                        </div>
                        <!-- Basic Floating Label Form section end -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
