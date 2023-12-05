<div>
<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input type="date" id="fromDate" wire:model="fromDate"
                     name="startDate" type="date" class="form-control" placeholder="YYYY-MM-DD HH:MM" required>
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input type="date" id="toDate" wire:model="toDate" name="endDate" type="date" class="form-control"
                     placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
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
<br>

<br>
@include('partials.stickymenu')
<br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-inverse">
            <div class="card-body">
                <div class="card-datatable table-responsive">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name of Distributor</th>
                                <th>Orders Assigned</th>
                                <th>Orders Fulfilled</th>
                                <th>Status</th>
                                <th>Fulfilment Rate</th>
                                {{-- <th>Region</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                                @forelse ($distributors as $key => $distributor)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $distributor->name}}</td>
                                    <td>{{ $distributor->orders_count}}</td>
                                    <td>{{ $distributor->orders_delivered_count}}</td>
                                    <td>@if ($distributor->orders_delivered_count > 0 && ($distributor->orders_count - $distributor->orders_delivered_count) !=0 )
                                        <p style="color: lightgreen">Fulfilled Partially</p>
                                        @elseif ($distributor->orders_count >0 && ($distributor->orders_count - $distributor->orders_delivered_count)===0)
                                        <p style="color: green">Hit</p>
                                        @elseif ($distributor->orders_count === 0)
                                        <p style="color: gray">No Orders</p>
                                        @elseif (($distributor->orders_count - $distributor->orders_delivered_count)===$distributor->orders_count)
                                        <p style="color: red">Missed</p>
                                    @endif</td>
                                    <td>
                                        @if($distributor->orders_count > 0)
                                            {{ number_format(($distributor->orders_delivered_count / $distributor->orders_count) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    {{-- <td></td> --}}

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No distributors found.</td>
                                </tr>
                        @endforelse

                        </tbody>
                    </table>
                    @if (!empty($distributors))
                    <div>
                        {{ $distributors->links() }}
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
