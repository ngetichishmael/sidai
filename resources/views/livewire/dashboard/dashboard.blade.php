    <div>
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="validationTooltip01">Start Date</label>
                            <input wire:model="start" name="startDate" type="date" class="form-control"
                                id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="validationTooltip01">End Date</label>
                            <input wire:model="end" name="startDate" type="date" class="form-control"
                                id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card card-statistics">
                <div class="card-header">
                    <h4 class="card-title">Statistics</h4>
                    <div class="d-flex align-items-center">
                        <p class="card-text font-small-2 mr-25 mb-0">Default Shows Monthly Report</p>
                    </div>
                </div>
                <div class="card">

                    <div class="card-body statistics-body mt-0">
                        <div class="row">
                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#vansalesSection" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">inventory</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            &nbsp;{{ number_format($vansales) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Van Sales</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#preorderSection" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">shopping_cart</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            {{ number_format($preorder) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Pre-Orders</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#buyingCustomersSection" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">arrow_forward</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            {{ number_format($customersCount) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Customers</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#distributorsOrders" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">order_approve</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            {{ number_format($orderfullmentbydistributors) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Distributor-Orders</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#orderFulfillmentSection" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">local_shipping</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            {{ number_format($orderfullment) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Deliveries</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-2 col-sm-4 col-12 mb-2 mb-xl-0">
                                <a href="#systemUsers" class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary">
                                        <div class="avatar-content">
                                            <span class="material-symbols-outlined">people</span>
                                        </div>
                                    </div> &nbsp;&nbsp;
                                    <div class="media-body my-auto pl-3 ml-3">
                                        <h4 class="font-weight-bolder ml-2" style="font-weight: bolder">
                                            {{ number_format($activeAll) }}</h4>
                                        <p class="card-text font-small-3 mb-0 font-medium-1"
                                            style="color: rgba(71,75,79,0.76)">Users</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
           <div class="row match-height">
              <div class="col-lg-8 col-12">
                 <div class="card card-company-table">
                    <div class="p-0 card-body">
                       <div class="table-responsive">
                          <div>
                              @livewire('dashboard.line-chart')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="col-lg-4 col-md-6 col-12">
              <div class="card card-developer-meetup">
                 <div class="text-center meetup-img-wrapper rounded-top">
                    <img src="{{ asset('app-assets/images/illustration/marketing.svg') }}" alt="Meeting Pic" height="170" />
                 </div>
                 <div class="card-body">
                    <div class="meetup-header d-flex align-items-center">
                       <div class="meetup-day">
                          <h6 class="mb-0">{{ date_format(now(), 'M') }}</h6>
                          <h3 class="mb-0">{{ date_format(now(), 'd') }}</h3>
                       </div>
                       <div class="my-auto">
                          <h4 class="card-title mb-25">Sidai Africa Ltd</h4>
                          <p class="mb-0 card-text">Tunza Mifugo Yako</p>
                       </div>
                    </div>
                    <div class="media" style="background: white">
                       <div class="mr-1 rounded avatar d-flex align-items-center" style="background: white">
                          <div class="avatar-content" style="background: white">
                             <i data-feather="calendar" class="avatar-icon font-medium-3" style="color: #4883ee;"></i>
                          </div>
                          &nbsp;&nbsp;&nbsp;
                          <div class="pl-3" style="background: white">
                             <h6 class="mb-0" style="color: gray;">{{ date_format(now(), 'D, F, Y') }}</h6>
                             <small style="color: darkgray;">8:AM to 5:PM</small>
                          </div>
                       </div>
                    </div>
                    <div class="media mt-2" style="background: white">
                       <div class="mr-1 rounded avatar align-items-center" style="background: white">
                          <div class="avatar-content">
                             <i data-feather="map-pin" class="avatar-icon font-medium-3"></i>
                          </div>
                          &nbsp;&nbsp;&nbsp;
                       <div class="media-body pl-3" style="background: white;">
                          <h6 class="mb-0" style="background: white;"> </h6>
                          <small style="background: white; color: darkgrey">Nairobi, Kenya</small>
                       </div>
                    </div>
                    </div>
                 </div>
              </div>
           </div>
           </div>
            @include('livewire.dashboard.table')

        </div>
        <br />
    </div>

