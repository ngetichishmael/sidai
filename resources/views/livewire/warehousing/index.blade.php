<div class="mb-1 row">
    <div class="col-md-10">
        <label for="">Search</label>
        <input type="text" wire:model="search" class="form-control" placeholder="Enter name">
    </div>
    <div class="col-md-2">
        <label for="">Items Per</label>
        <select wire:model="perPage" class="form-control">`
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </div>
</div>
<div class="card card-default">
    <div class="card-body">
        <table class="table table-striped table-bordered table-responsive">
            <thead>
                <th width="1%">#</th>
                <th>Name</th>
                <th>Location</th>
                <th>City</th>
                <th>Country</th>
                <th>Phone number</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </thead>
            <tbody>
                @foreach ($warehouses as $count => $warehouse)
                    <tr>
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $warehouse->name !!}</td>
                        <td>{!! $warehouse->location !!}</td>
                        <td>{!! $warehouse->city !!}</td>
                        <td>{!! $warehouse->country !!}</td>
                        <td>{!! $warehouse->phone_number !!}</td>
                        <td>{!! $warehouse->email !!}</td>
                        <td>{!! $warehouse->status !!}</td>
                        <td>
                            <a href="{!! route('warehousing.edit', $warehouse->warehouse_code) !!}" class="btn btn-primary btn-sm">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-1">
            {{ $warehouses->links() }}
        </div>
    </div>
</div>
</div>
