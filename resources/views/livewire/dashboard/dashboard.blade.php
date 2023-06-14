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
                                <a href="#orderFulfillmentSection" class="d-flex align-items-center">
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
                                <a href="#vansalesSection" class="d-flex align-items-center">
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
            <div class="row">
                <div class="col-md-12">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-8">
                              @livewire('dashboard.line-chart')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('livewire.dashboard.table')

        </div>
        <br />
    </div>

