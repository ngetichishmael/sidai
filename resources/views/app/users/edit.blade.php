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
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Users</a></li>
                            <li class="breadcrumb-item active" >Edit</li>
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
                                                    <label for="select-country">Current User Role:
                                                        {{ $edit->account_type }}</label>
                                                    <select class="form-control select2" id="select-country"
                                                        name="account_type" required>
                                                       <option value="{{ $edit->account_type }}" selected>{{ $edit->account_type }}</option>
                                                       @foreach ($roles as $value)
                                                          <option value="{{ $value->name }}">{{ $value->name }}-{{ $value->description }}
                                                          </option>
                                                       @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-2 col-md-6 col-12 ">
                                                <div class="form-group">
                                                    <label for="select-country">Current Status:
                                                        {{ $edit->status }}</label>
                                                    <select class="form-control select2" id="select-action" name="status"
                                                        required>
                                                        <option value="{{ $edit->status }}"> {{ $edit->status }}</option>
                                                        <option value="Active">Active</option>
                                                        <option value="Suspend">Suspend</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="select-country">Current User Region: {{$user_region->name ?? ''}} </label>
                                                    <select class="form-control select2" id="select-country" name="region"
                                                        required>
                                                       <option value="{{$user_region->id ?? ''}}" selected>{{$user_region->name ?? ''}}</option>
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
                                    </div>
                                   <div class="row mt-1">
                                      <div class="col-8"></div>
                                      <div class="col-4 d-flex justify-content-end">
                                         <button type="submit" class="mb-1 mr-1 btn btn-primary"> Update </button>&nbsp;&nbsp;
                                         <a href="{{ url()->previous() }}" type="reset" class="mb-1 mr-1 btn btn-outline-secondary"> Cancel </a>
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
