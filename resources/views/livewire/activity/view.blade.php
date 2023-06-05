@extends('layouts.app')
{{-- page header --}}
@section('title', 'Activity Logs Tracking')
{{-- page styles --}}
@section('stylesheets')

@endsection


{{-- content section --}}
@section('content')
    <section class="app-user-view">
        <!-- User Card & Plan Starts -->
        <div class="row">
            <!-- User Card starts-->
            <div class="col-xl-9 col-lg-8 col-md-7">
                <div class="card user-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-lg-12 d-flex flex-column justify-content-between border-container-lg">

                               <div class="user-avatar-section">
                                  <div class="d-flex justify-content-start">

                                     <div class="d-flex flex-column ml-1">
                                        <div class="user-info mb-1">
                                           <h4 class="mb-0">Name : {{ $activity->user->name ?? 'NA'}}</h4>
                                        </div>

                                        <div class="card">
                                           <h5 class="mb-1">Action     <span class="badge badge-light-secondary"
                                              >{{ $activity->activity ?? ''}} <span class="nextYear"></span></span>
                                           </h5>

                                        </div>
                                        <div class="card">
                                           <h5 class="mb-1">Activity     <span class="badge badge-light-secondary"
                                              >{{ $activity->action ?? ''}} <span class="nextYear"></span></span>
                                           </h5>

                                        </div>
                                        <div class="card">
                                           <h5 class="mb-1">Time     <span class="badge badge-light-secondary"
                                              >{{ $activity->created_at ?? ''}} <span class="nextYear"></span></span>
                                           </h5>

                                        </div>
                                        <div class="card">
                                           <h5 class="mb-1">Device IP Address <span class="badge badge-light-secondary"
                                              >{{ $activity->ip_address ?? ''}} <span class="nextYear"></span></span>
                                           </h5>

                                        </div>

                                     </div>

                                  </div>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /User Card Ends-->
        </div>




        <div class="card">

            <div class="pt-0 card-datatable table-responsive">
                <div class="card-datatable table-responsive">
                    <div class="card-title pt-2 pb-1 pl-4" style="align-content: center; color: orange">
                        Other trail of actions performed by {!! $activity->user->name ?? 'N/A' !!}
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Section</th>
                            <th>Name</th>
                            <th>IP address</th>
                            <th>Date</th>
                            <th>Activity</th>

                        </tr>
                        </thead>
                        <tbody>

                        @forelse ($activities as $key => $activity)
                            <tr>
                                <td>{{ $activity->section ?? 'N/A' }}</td>
                                <td>{{ $activity->user->name ?? 'N/A' }}</td>
                                <td>{{ $activity->ip_address ?? 'N/A' }}</td>
                                <td>{!! $activity->created_at ?? now() !!}</td>
                                <td>{{$activity->activity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center;"> No Record Found </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
{{--                    <div class="mt-1">{{ $activities->links() }}--}}
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex flex-wrap">
            <a href="{{route('activity.index')}}" style="margin-left: 65%;" class="btn btn-primary"> Back </a>
        </div>







    </section>
@endsection


