@extends('layouts.app')
{{-- page header --}}
@section('title', 'Edit User')



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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Users</a></li>
                            <li class="breadcrumb-item active">Edit</li>
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
                    <form method="POST"
                        action="{{ route('user.update', [
                            'id' => $user_code,
                        ]) }}"
                        style="gap: 20px;">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-2 col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">User Name</label>
                                                    <input type="text" id="first-name-column" class="form-control"
                                                        placeholder="User Name" value="{{ $edit->name }}" name="name"
                                                        required />
                                                </div>
                                            </div>
                                            <div class="mb-2 col-md-6 col-12 ">
                                                <div class="form-group">
                                                    <label for="last-name-column">Last Name</label>
                                                    <input type="email" id="last-name-column" class="form-control"
                                                        value="{{ $edit->email }}" placeholder="Email" name="email"
                                                        required />
                                                </div>
                                            </div>
                                            <div class="mb-2 col-md-6 col-12 ">
                                                <div class="form-group">
                                                    <label for="city-column">Phone Number</label>
                                                    <input type="tel" id="city-column" class="form-control"
                                                        pattern="[0789][0-9]{9}" value="{{ $edit->phone_number }}"
                                                        name="phone_number" required />
                                                </div>
                                            </div>
                                            <div class="mb-2 col-md-6 col-12 ">
                                                <div class="form-group">
                                                    <label for="select-country">Current Type:
                                                        {{ $edit->account_type }}</label>
                                                    <select class="form-control select2" id="select-country"
                                                        name="account_type" required>
                                                       <option value="">Select Category</option>
                                                       <option value="NSM">National Sales Manager</option>
                                                       <option value="RSM">Regional Sales Manager</option>
                                                       <option value="TSR">Technical Sales Representative</option>
                                                       <option value="Shop-Attendee">Shop Attendee</option>
                                                       <option value="TD">Trade Developer</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-2 col-md-6 col-12 ">
                                                <div class="form-group">
                                                    <label for="select-country">Current Status:
                                                        {{ $edit->status }}</label>
                                                    <select class="form-control select2" id="select-action" name="status"
                                                        required>
                                                        <option value="">Select Action</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Suspend">Suspend</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="select-country">Region</label>
                                                    <select class="form-control select2" id="select-country" name="region"
                                                        required>
                                                        <option value="">Region</option>
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
                                                                        id="admin-read" name="van_sales"
                                                                        @if ($permissions->van_sales === 'YES') checked @endif />
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
                                                                        id="staff-read" name="new_sales"
                                                                        @if ($permissions->new_sales === 'YES') checked @endif />
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
                                                                        id="author-read" name="deliveries"
                                                                        @if ($permissions->deliveries === 'YES') checked @endif />
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
                                                                        @if ($permissions->schedule_visits === 'YES') checked @endif />
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
                                                                        id="user-read" name="merchanizing"
                                                                        @if ($permissions->merchanizing === 'YES') checked @endif />
                                                                    <label class="custom-control-label"
                                                                        for="user-read"></label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-2 col-12 d-flex flex-sm-row flex-column" style="gap: 20px;">
                                            <button type="submit"
                                                class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Update</button>
                                            <a href="{{ route('users.index') }}" type="reset"
                                                class="btn btn-outline-secondary">Cancel</a>
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
