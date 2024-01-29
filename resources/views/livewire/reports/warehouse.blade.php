<div>
<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
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
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Warehouse Name</th>
                                <th>Shop Attendees</th>
                                <th>Region</th>
                                <th>Quantity</th>
                                <th>No of Allocations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->Manager->name ?? 'Not Assigned' }}</td>
                                    <td class="cell-fit">
                                        {{ $warehouse->subregion->name ?? '' }},
                                        {{ $warehouse->region->name ?? '' }}
                                    </td>
                                    <td>{{ $warehouse->products_count }}</td>
                                    <td>{{ $this->allocated($warehouse->warehouse_code) }}</td>
                                    <td><a href="{{ route('details.allocation',['warehouse_code'=>$warehouse->warehouse_code]) }}" class="btn btn-outline-success">View</a></td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
