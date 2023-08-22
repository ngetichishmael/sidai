<div class="row">
{{--    <div class="col-md-3">--}}
{{--        <label for="">Status</label>--}}
{{--        <select wire:model="status" class="form-control">--}}
{{--            <option value="" selected>select</option>--}}
{{--            <option value=""></option>--}}
{{--            <option value="">Pending Delivery</option>--}}
{{--            <option value="">Waiting Acceptance</option>--}}
{{--            <option value="">Delivered</option>--}}
{{--        </select>--}}
{{--    </div>--}}
   <div class="mb-2 row">
      <div class="col-md-9">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
   <div class="col-md-3">
      <label for="">Items Per</label>
      <select wire:model="perPage" class="form-control">`
         <option value="10" selected>10</option>
         <option value="25">25</option>
         <option value="50">50</option>
         <option value="100">100</option>
      </select>
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

                            @foreach ($lifted as $key => $lift)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    {{-- <td>{{ $lifted->code }}</td> --}}
                                    {{-- <td>{{ $lifted->name }}</td> --}}
                                    <td>{{ $lift->user_name }}</td>
                                    <td>{{ $lift->qty??'' }}</td>
                                    <td>{{ $lift->user_region??'' }}</td>
                                    <td>{{ $lift->warehouse }}</td>
                                    <td>{{ $lift->date }}</td>
                                    <td><a href="{{ URL('lifted/items/' . $lift->code) }}" class="btn btn-sm"
                                            style="color:white;background-color:rgb(202, 50, 50)">View</a></td>
                                </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
               <div class="mt-1">{{ $lifted->links() }}</div>
            </div>
        </div>
    </div>
</div>
