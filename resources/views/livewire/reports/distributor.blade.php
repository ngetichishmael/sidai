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
    <div class="col-md-3">
        <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
            data-toggle="tooltip" data-placement="top" title="Export Excel">
            <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
        </button>
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
                                <th>Rejected Orders</th>
                                <th>Fulfilment Rate</th>
                                <th>Region</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse ($distributors as $key => $distributor)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $distributor->name}}</td>
                                    <td>{{ $distributor->orders_count}}</td>
                                    <td>{{ $distributor->orders_delivered_count}}</td>
                                    <td></td>
                                    <td>
                                        @if($distributor->orders_count > 0)
                                            {{ number_format(($distributor->orders_delivered_count / $distributor->orders_count) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td></td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">No distributors found.</td>
                                </tr>
                        @endforelse
                        
                        </tbody>
                    </table>
                    {{ $distributors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
