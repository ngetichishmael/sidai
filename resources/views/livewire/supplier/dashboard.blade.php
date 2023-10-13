<div class="row">
<div class="col-md-3">
   <label for="">Search by distributor name, route, region</label>
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
<div class="card card-default">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="1%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone number</th>
                    <th>Date addded</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $count => $supplier)
                    <tr {{-- class="success" --}}>
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $supplier->name !!}</td>
                       <td>{!! \Illuminate\Support\Str::limit($supplier->email, 25) !!}</td>
                       <td>{!! $supplier->phone_number !!}</td>
                        <td>{!! date('d F, Y', strtotime($supplier->created_at)) !!}</td>
                        <td>
                            <div class="d-flex" style="gap: 20px">
                                <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-sm" style="background-color: #B6121B;color:white">
                                    <span>Edit</span>
                                </a>
                                <a href="{!! route('supplier.archive', $supplier->id) !!}" class="btn btn-sm delete" style="background-color: #db0610;color:white">
                                    <span>Archive</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                @if(empty($suppliers))
                   <div>
                      <tr>
                         <td colspan="6"> No Distributor(s) Found ...</td>
                      </tr>
                   </div>
                @endif
            </tbody>
        </table>

        <div class="mt-1">{!! $suppliers->links() !!}</div>
    </div>
</div>
