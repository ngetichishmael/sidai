<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
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
<div class="row">
    @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-inverse">
            <div class="card-body">
                <table id="data-table-default" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Region</th>
                            <th>No of Orders</th>
                            <th>No of Customers</th>
                            <th>No of Deliveries</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regions as $count => $region)
                            <tr>
                                <td>{{ $count + 1 }}</td>
                                <td>{{ $region->name }}</td>
                                <td>{{ $this->orders($region->id) }}</td>
                                <td>{{ $this->customers($region->id) }}</td>
                                <td>{{ $this->deliveries($region->id) }}</td>
                                <td><a href="{{ route('regional.order',['id'=>$region->id]) }}" class="btn btn-outline-success">View</a></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
