
<div class="row">
    <div class="col-md-3">
        <label for="">Status</label>
        <select wire:model="status" class="form-control">
            <option value="" selected>select</option>
            <option value=""></option>
            <option value="">Pending Delivery</option>
            <option value="">Waiting Acceptance</option>
            <option value="">Delivered</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
</div>
<br>
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
                                <th>Warehouse</th>
                                <th>Region</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as$key => $warehouse )
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $warehouse->name??'' }}</td>
                                <td>{{ $warehouse->region->name??'' }}</td>
                                <td><a href="{{ URL('salesperson/reconciled/' . $warehouse->warehouse_code) }}" class="btn btn-sm" style="color: white;background-color:rgb(194, 51, 51)">View</a></td>
                            </tr>

                            @endforeach


                        </tbody>
                    </table>
                </div>
                {{-- <div class="mt-1">{!! $preorders->links() !!}</div> --}}
            </div>
        </div>
    </div>
</div>
