@extends('layouts.app')
{{-- page header --}}
@section('title', 'User Details')

{{-- content section --}}
@section('content')
   <div class="content-header row">
      <div class="content-header-left col-md-12 col-12 mb-2">
         <div class="row breadcrumbs-top">
            <div class="col-12">
               <h2 class="content-header-title float-start mb-0"><i data-feather="users"></i> User Detail</h2>
               <div class="breadcrumb-wrapper">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                     <li class="breadcrumb-item active"><a href="/users-Roles">Roles List</a></li>
                     <li class="breadcrumb-item active"><a href="#">User Details</a></li>
                  </ol>
               </div>
            </div>
         </div>
      </div>
   </div>
<!-- Basic multiple Column Form section start -->
<section id="multiple-column-form">
   <div class="row">
      <div class="col-10">
         <div class="card">
            <div class="card-header">
{{--               <h4 class="card-title">User Details</h4>--}}
            </div>
            <div class="card-body">

               <div class="row">
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>User Name:</h4>
                        <p style="color:black">{{ $user->name??'N/A'}}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Roles(s)</h4>
                        <p style="color:black">
                        @if (empty($role_detail))
                           <p>No role details found.</p>
                        @else
                           @foreach ($user->roles as $role)
                              <span style="color: #007bff; font-weight: bolder">{{ ucfirst($role->name) }}</span>
                           @endforeach</p>
                              @endif
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Permission(s)</h4>
                        @if (empty($role_detail))
                           <p>No role permissions found.</p>
                        @else
                           @foreach ($role_detail as $role)
                              @if ($role->permissions->count() > 0)
                                 <ul style="color: black;">
                                    @foreach ($role->permissions as $permission)
                                       <ul>
                                          <span style="color: #007bff; font-weight: bolder">{{ ucfirst($permission->name) }}</span>
                                       </ul>
                                    @endforeach
                                 </ul>
                              @else
                                 <p>No permissions found for this user role.</p>
                              @endif
                           @endforeach
                        @endif

                     </div>
                  </div>

                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Status:</h4>
                        <p style="color:black">{{ $user->status ?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Email:</h4>
                        <p style="color:black">{{ $user->email ?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Phone:</h4>
                        <p style="color:black">{{ $user->phone ?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Created At:</h4>
                        <p style="color:black">{{ $user->created_at?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Region</h4>
                        <p style="color:black">{{ $user->region->name ?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Sub-Region</h4>
                        <p style="color:black">{{ $user->subregion->name ?? 'N/A' }}</p>
                     </div>
                  </div>
                  <div class="col-md-6 col-12">
                     <div class="form-group">
                        <h4>Route</h4>
                        <p style="color:black">{{ $user->route->name ?? 'N/A' }}</p>
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
                              <span class="align-middle">Other System Permissions Out Side Customer Shop</span>
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
                     <br>

                  </div>
               </form>
               <!-- users edit account form ends -->
            </div>
            <!-- Account Tab ends -->
         </div></div></div>
   <div class="row  mt-3 mb-5">
      <div class="col-10"></div>
      <div class="col-2">
         <a href="{{ url()->previous() }}" class="btn btn-md" style="color: white; background: #fc7d50">Back</a>
      </div>
   </div>
</section>
@endsection
{{-- page scripts --}}
@section('script')

@endsection
