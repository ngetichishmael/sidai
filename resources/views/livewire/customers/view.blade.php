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
                             <span>{{ Str::upper($customer->customer_name ?? '') }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">E Wallet:</span>
                             <span>{{ number_format($customer->wallet->amount ?? 0, 2) }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Email:</span>
                             <span>{{ $customer->email ?? '' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">PIN Number</span>
                             <span>{{ $customer->vat_number ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Approved</span>
                             <span>{{ $customer->approval ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Address:</span>
                             <span>{{ $customer->address ?? '' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Customer Group:</span>
                             <span>{{ $customer->customer_group ?? '' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Contact:</span>
                             <span>{{ $customer->phone ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Email:</span>
                             <span>{{ $customer->email ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Status:</span>
                             <span>{{ $customer->status ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Created At:</span>
                             <span>{{ $customer->created_at ?? 'N/A' }}</span>
                         </li>
                         <li class="mb-2 pt-1">
                             <span class="fw-semibold me-1">Creator:</span>
                             <span>{{ $customer->Creator->name ?? '' }}</span>
                         </li>

                         <div class="my-1 col-sm-9 offset-sm-3">
                             @if ($customer->id != null)
                                 <a href="{{ route('creditor.approve', $customer->id) }}"
                                     class="btn btn-success">Approve To Creditor</a>
                             @endif
                         </div>



                     </ul>
                 </div>
             </div>
         </div>
     </div>
     <!--/ User Sidebar -->


     <!-- User Content -->
     <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
         <!-- User Pills -->
         <ul class="nav nav-pills flex-column flex-md-row mb-4">
             <li class="nav-item">
                 <a class="nav-link active" data-bs-toggle="tab" href="#visits-tab">
                     <i class="ti ti-user-check ti-xs me-1"></i>Visits
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" data-bs-toggle="tab" href="#orders-tab">
                     <i class="ti ti-lock ti-xs me-1"></i>Orders
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" data-bs-toggle="tab" href="#payments-tab">
                     <i class="ti ti-currency-dollar ti-xs me-1"></i>Payments
                 </a>
             </li>
         </ul>

         <div class="tab-content">
             <div class="tab-pane fade show active" id="visits-tab">
                 <!-- Visits Timeline -->
                 @livewire('customers.visit', [
                     'customer_id' => $customer_id,
                 ])
                 <!-- /Visits Timeline -->
             </div>
             <div class="tab-pane fade" id="orders-tab">
                 <!-- Orders Timeline -->
                 @livewire('customers.orderdetails', [
                     'customer_id' => $customer_id,
                 ])
                 <!-- /Orders Timeline -->
             </div>
             <div class="tab-pane fade" id="payments-tab">
                 <!-- Payments Timeline -->
                 @livewire('customers.payment', [
                     'customer_id' => $customer_id,
                 ])
                 <!-- /Payments Timeline -->
             </div>
         </div>
     </div>

     <!--/ User Content -->
 </div>
