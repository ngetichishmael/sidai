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
                    <th width="18%">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $count => $supplier)
                    <tr {{-- class="success" --}}>
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $supplier->name !!}</td>
                        <td>{!! $supplier->email !!}</td>
                        <td>{!! $supplier->phone_number !!}</td>
                        <td>{!! date('d F, Y', strtotime($supplier->created_at)) !!}</td>
                        <td>
                            <div class="d-flex" style="gap: 20px">
                                <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-sm" style="background-color: #B6121B;color:white">
                                    <span>Edit</span>
                                </a>
                                <a href="{!! route('supplier.destroy', $supplier->id) !!}" class="btn btn-sm delete" style="background-color: #db0610;color:white">
                                    <span>Delete</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-1">{!! $suppliers->links() !!}</div>
    </div>
</div>
