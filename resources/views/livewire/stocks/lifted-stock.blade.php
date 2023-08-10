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
                                {{-- <th>Allocation Code</th> --}}
                                {{-- <th>Product Name</th> --}}
                                <th>Sales Agent</th>
                                <th>Quantity</th>
                                <th>Region</th>
                                <th>Source</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($lifted as $key => $lifted)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    {{-- <td>{{ $lifted->code }}</td> --}}
                                    {{-- <td>{{ $lifted->name }}</td> --}}
                                    <td>{{ $lifted->user_name }}</td>
                                    <td>{{ $lifted->qty??'' }}</td>
                                    <td>{{ $lifted->user_region??'' }}</td>
                                    <td>{{ $lifted->warehouse }}</td>
                                    <td>{{ $lifted->date }}</td>
                                    <td><a href="{{ URL('lifted/items/' . $lifted->code) }}" class="btn btn-sm"
                                            style="color:white;background-color:rgb(202, 50, 50)">View</a></td>
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
