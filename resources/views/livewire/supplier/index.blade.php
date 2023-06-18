<div class="card card-default">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Quantity Of Inventory</th>
                    <th>Action</th>
                 </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $key => $supplier )
                <tr>
                   <td>{{ $key+1 }}</td>
                   <td>{{ $supplier->Customer->customer_name }}</td>
                   <td></td>
                   <td><a href="" class="btn btn-sm" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
               </tr>
               
                @endforeach
               
                  
               </tbody>
        </table>

        {{-- <div class="mt-1">{!! $suppliers->links() !!}</div> --}}
    </div>
</div>
