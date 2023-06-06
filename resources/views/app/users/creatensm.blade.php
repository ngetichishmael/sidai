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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">National Sales Manager</a></li>
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
{{--                                            <div class="mb-2 col-md-6 col-12">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="first-name-column">Employee Code</label>--}}
{{--                                                    <input type="text" id="first-name-column" class="form-control"--}}
{{--                                                        placeholder="Employee Code" name="employee_code" required />--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
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


                                                    <input id="region" type="hidden" name="region" value="0000"  />
                                                    <input id="region" type="hidden" name="account_type"  id="account-type" value="NSM"  />


{{--                                            <div class="col-md-6 col-12" type="hidden">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="select-country">User Type</label>--}}
{{--                                                    <select class="form-control select2 select" id="account-type"--}}
{{--                                                        name="account_type" required>--}}
{{--                                                        <option value=""></option>--}}
{{--                                                       <option value="NSM">National Sales Manager</option>--}}
{{--                                                       <option value="RSM">Regional Sales Manager</option>--}}
{{--                                                       <option value="TSR">Technical Sales Representative</option>--}}
{{--                                                       <option value="Shop-Attendee">Shop Attendee</option>--}}
{{--                                                       <option value="TD">Trade Developer</option>--}}

{{--                                                    </select>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6 col-12">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="select-region">Regions</label>--}}
{{--                                                    <select class="form-control select2" id="region" name="region"--}}
{{--                                                        required>--}}
{{--                                                        <option value="">Select a Region</option>--}}
{{--                                                        @foreach ($regions as $value)--}}
{{--                                                            <option value="{{ $value->id }}">{{ $value->name }}--}}
{{--                                                            </option>--}}
{{--                                                        @endforeach--}}
{{--                                                    </select>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <!-- Account Tab starts -->
{{--                            <div class="tab-pane active" id="account" type="hidden" aria-labelledby="account-tab" role="tabpanel">--}}
{{--                                <form class="form-validate">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-12">--}}
{{--                                            <div class="mt-1 border rounded table-responsive">--}}
{{--                                                <h6 class="py-1 mx-1 mb-0 font-medium-2">--}}
{{--                                                    <i data-feather="lock" class="font-medium-3 mr-25"></i>--}}
{{--                                                    <span class="align-middle">Permission Out Side Customer Shop</span>--}}
{{--                                                </h6>--}}
{{--                                                <table class="table table-striped table-borderless">--}}
{{--                                                    <thead class="thead-light">--}}
{{--                                                        <tr>--}}
{{--                                                            <th>Module</th>--}}
{{--                                                            <th>Permission</th>--}}
{{--                                                        </tr>--}}
{{--                                                    </thead>--}}
{{--                                                    <tbody>--}}
{{--                                                        <tr>--}}
{{--                                                            <td>Van Sales</td>--}}
{{--                                                            <td>--}}
{{--                                                                <div class="custom-control custom-checkbox">--}}
{{--                                                                    <input type="checkbox" class="custom-control-input"--}}
{{--                                                                        id="admin-read" name="van_sales" checked />--}}
{{--                                                                    <label class="custom-control-label"--}}
{{--                                                                        for="admin-read"></label>--}}
{{--                                                                </div>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td>New Sales</td>--}}
{{--                                                            <td>--}}
{{--                                                                <div class="custom-control custom-checkbox">--}}
{{--                                                                    <input type="checkbox" class="custom-control-input"--}}
{{--                                                                        id="staff-read" name="new_sales" checked />--}}
{{--                                                                    <label class="custom-control-label"--}}
{{--                                                                        for="staff-read"></label>--}}
{{--                                                                </div>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td>Deliveries</td>--}}
{{--                                                            <td>--}}
{{--                                                                <div class="custom-control custom-checkbox">--}}
{{--                                                                    <input type="checkbox" class="custom-control-input"--}}
{{--                                                                        id="author-read" name="deliveries" checked />--}}
{{--                                                                    <label class="custom-control-label"--}}
{{--                                                                        for="author-read"></label>--}}
{{--                                                                </div>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td>Schedule Visits </td>--}}
{{--                                                            <td>--}}
{{--                                                                <div class="custom-control custom-checkbox">--}}
{{--                                                                    <input type="checkbox" class="custom-control-input"--}}
{{--                                                                        id="contributor-read" name="schedule_visits"--}}
{{--                                                                        checked />--}}
{{--                                                                    <label class="custom-control-label"--}}
{{--                                                                        for="contributor-read"></label>--}}
{{--                                                                </div>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <td>Merchanizing</td>--}}
{{--                                                            <td>--}}
{{--                                                                <div class="custom-control custom-checkbox">--}}
{{--                                                                    <input type="checkbox" class="custom-control-input"--}}
{{--                                                                        id="user-read" name="merchanizing" checked />--}}
{{--                                                                    <label class="custom-control-label"--}}
{{--                                                                        for="user-read"></label>--}}
{{--                                                                </div>--}}
{{--                                                            </td>--}}
{{--                                                        </tr>--}}
{{--                                                    </tbody>--}}
{{--                                                </table>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="mt-2 col-12 d-flex flex-sm-row flex-column" style="gap: 20px;">--}}
{{--                                            <button type="submit" class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Save--}}
{{--                                                Changes</button>--}}
{{--                                            <a href="{{ route('users.index') }}" type="reset"--}}
{{--                                                class="btn btn-outline-secondary">Cancel</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                                <!-- users edit account form ends -->--}}
{{--
     </div>--}}

                           <input type="hidden" class="custom-control-input"
                                  id="user-read" name="merchanizing" value="merchanizing" checked />
                           <input type="hidden" class="custom-control-input"
                                  id="contributor-read" name="schedule_visits" value="schedule_visits"
                                  checked />
                           <input type="hidden" class="custom-control-input"
                                  id="author-read" name="deliveries" value="deliveries" checked />
                           <input type="hidden" class="custom-control-input"
                                  id="staff-read" name="new_sales" value="new_sales" checked />
                           <input type="hidden" class="custom-control-input"
                                  id="admin-read" name="van_sales" value="van_sales" checked />

                           <div class="mt-2 col-12 d-flex flex-sm-row flex-column" style="gap: 20px;">
                              <button type="submit" class="mb-1 mr-0 btn btn-primary mb-sm-0 mr-sm-1">Save User</button>
                              <a href="{{ route('users.index') }}" type="reset"
                                 class="btn btn-outline-secondary">Cancel</a>
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
