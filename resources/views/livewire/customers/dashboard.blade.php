<div>
    <div>
       <div>
        <div class="row mb-1 mt-1">
            <div class="col-md-3 col-sm-4">
                <label for="">Start Date</label>
                <input wire:model="startDate" type="date" class="form-control">
            </div>
            <div class="col-md-3 col-sm-4">
                <label for="">End Date</label>
                <input wire:model="endDate" type="date" class="form-control">
            </div>
           <div class="col-md-3">
              <label for="">Flter By Customer Status</label>
              <select wire:model="selectedStatus" class="form-control">
                 <option value="all"> All </option>
                 <option value="active"> Active </option>
                 <option value="partially_inactive">Partially Inactive</option>
                 <option value="inactive"> Inactive </option>
                 <option value="new"> New </option>
                 <option value="new_inactive"> New-Inactive </option>
              </select>
           </div>
            <div class="col-md-3 mb-2">
                <label for="">Export Reports:</label>
                <div class="dropdown">
                    <button style="background-color: #B6121B;color:white" class="mr-2 btn btn-md dropdown-toggle"
                        type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true"
                        aria-expanded="false" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="15" height="13">
                        Export
                    </button>
                    <div class="dropdown-menu dropdown-menu-left">
                        <a class="dropdown-item" wire:click="export">Excel</a>
                        <a class="dropdown-item" wire:click="exportCSV"> CSV</a>
                        <a class="dropdown-item" wire:click="exportPDF">PDF</a>
                    </div>
                </div>
            </div>
        </div>
       </div>
       <div>
        <div class="mb-1 row">
            <div class="col-md-6">
                <label for="">Search by user name, route, region</label>
                <input type="text" wire:model="search" class="form-control"
                    placeholder="Enter customer name, created by, subregion, town, region">
            </div>
            <div class="col-md-3">
                <label for="">Flter By Group</label>
                <select wire:model="selectedGroup" class="form-control">
                    <option value="" selected>select</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->name }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="">Items Per page</label>
                <select wire:model="perPage" class="form-control">`
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                </select>
            </div>
        </div>
    </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <div class="card-datatable table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Region, Subregion, Town</th>
                        <th>Outlet</th>
                        <th>Added By</th>
                        <th>Status</th>
                        <th>Order Status</th>
                        <th>Date Created</th>
                        <th width="15%">Action</th>
                    </thead>
                    <tbody>
                        @forelse($contacts as $count => $contact)
                            <td>{{ $count + 1 }}</td>
                            <td>{{ $contact->customer_name }}</td>
                            <td>{{ $contact->customer_number }}</td>
                            <td class="cell-fit">
                                {!! $contact->region_name !!},
                                <i>{!! $contact->subregion_name !!}</i>,
                                {!! $contact->area_name !!}
                            </td>
                            <td>{{ $contact->customer_group??'' }}</td>

                            <td>{{ $this->getCreatorName($contact->user_code) }}</td>
                            @php
                               $lastOrderDate = $contact->last_order_date ? \Carbon\Carbon::parse($contact->last_order_date) : null;
                                                  $now = \Carbon\Carbon::now();
                                                      if ($lastOrderDate !== null) {
                                                          $differenceInMonths = $lastOrderDate->diffInMonths($now);
                                                      } else {
                                                          $differenceInMonths = null;
                                                      }
                                                  $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3);
                                                  $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
                                                  $createdAtDate = \Carbon\Carbon::parse($contact->created_at);
                                                   $daysDifference = $oneMonthAgo->diffInDays($createdAtDate);
                            @endphp

                            @if ($lastOrderDate != null && $lastOrderDate->lessThanOrEqualTo($oneMonthAgo))
                               <td><span class="badge btn-outline-success">Active</span></td>
                            @elseif ($lastOrderDate != null && ($daysDifference >= 30 && $daysDifference <= 90))
                               <td><span class="badge btn-outline-info">Partially Inactive</span></td>
                            @elseif ($lastOrderDate != null && $daysDifference >= 90)
                               <td><span class="badge btn-outline-danger">Inactive</span></td>
                            @elseif ($lastOrderDate === null && $daysDifference <= 30)
                               <td><span class="badge btn-outline-secondary"> New </span></td>
                            @elseif ($lastOrderDate === null && $daysDifference > 30)
                               <td><span class="badge btn-outline-warning"> New Inactive </span></td>
                            @else
                               <td><span class="badge btn-outline-warning"> Inactive </span></td>
                            @endif
                            <td style="color: green">{{ $contact->order_status ??'unknown' }}</td>
                            <td>{!! $contact->updated_at->format('d/m/Y') ?? $contact->created_at->format('d/m/Y') !!}</td>
                            <td>
                                <div class="dropdown">
                                    <button style="background-color: #B6121B;color:white"
                                        class="mr-2 btn btn-md dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-bs-trigger="click" aria-haspopup="true" aria-expanded="false"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <i data-feather="eye"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('customer.edit', $contact->id) }}">
                                            <i data-feather='edit' class="mr-50"></i>
                                            <span>Edit</span>
                                        </a>
                                        <a class="dropdown-item" href="{{ route('creditor.details', $contact->id) }}">
                                            <i data-feather='eye' class="mr-50"></i>
                                            <span>View</span>
                                        </a>
                                        @if ($contact->approval === 'Approved')
                                            <a wire:click.prevent="deactivate({{ $contact->id }})"
                                                onclick="confirm('Are you sure you want to DEACTIVATE this customer?')||event.stopImmediatePropagation()"
                                                type="button" class="dropdown-item btn btn-sm me-2">
                                               <i data-feather="check"></i>Approved</a>
                                        @else
                                            <a wire:click.prevent="activate({{ $contact->id }})"
                                                onclick="confirm('Are you sure you want to ACTIVATE this customer?')||event.stopImmediatePropagation()"
                                                type="button" class="dropdown-item btn btn-sm me-2"><i
                                                    data-feather="pause"></i>&nbsp;Pending</a>
                                        @endif
                                    </div>
                                </div>

                            </td>
                            </tr>
                        @empty
                            <div>
                                <tr>
                                    <td colspan="10" class="text-center"> No Customer(s) Found ...</td>
                                </tr>
                            </div>
                        @endforelse
                    </tbody>
                </table>
                @if (!empty($contacts))
                    <div>
                        {{ $contacts->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
