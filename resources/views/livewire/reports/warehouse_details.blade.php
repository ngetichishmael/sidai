<div class="row">
    <!-- User Sidebar -->
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <!-- User Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="user-avatar-section">
                    <div class=" d-flex align-items-center flex-column">
                        @php
                            $imageUrl = Storage::url($customer->image);
                            if (!$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                                $imageUrl = asset('app-assets/images/sidaiweblogo.png');
                            }
                        @endphp

                        <img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $imageUrl }}" height="100"
                            width="100" alt="User avatar">
                        <h4 class="mb-2">{{ Str::upper($customer->customer_name ?? '') }}</h4>

                    </div>
                </div>
                <p class="mt-4 small text-uppercase text-muted">Details</p>
                <div class="info-container">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="fw-semibold me-1">Name:</span>
                            <span></span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">E Wallet:</span>
                            <span></span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Email:</span>
                            <span></span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">PIN Number</span>
                            <span></span>
                        </li>
                        <li class="mb-2 pt-1">
                            <span class="fw-semibold me-1">Approved</span>
                            <span></span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--/ User Sidebar -->


    
</div>
