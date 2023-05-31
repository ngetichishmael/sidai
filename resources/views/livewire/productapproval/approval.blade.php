<div>
    <div class="row">
       <div class="col-md-12 mb-1">
 
       </div>
       <div class="col-md-12">
          <div class="card">
             <div class="card-body">
                <table class="table table-bordered table-striped">
                   <thead>
                      <th>#</th>
                      <th>User Name</th>
                      {{-- <th>Total Items</th> --}}
                      <th>Quantity</th>
                      <th>Type</th>
                      <th>Status</th>
                      <th>Created On</th>
                      <th>Action</th>
                   </thead>
                   <tbody>
                      @foreach($products as $count=>$product)
                         <tr>
                            <td>{!! $count+1 !!}</td>
                            <td>{!! $product->product_name !!}</td>
                            {{-- <td>{!! Sales::total_allocated_items($allocation->allocation_code)->sum('current_qty') !!}</td> --}}
                            
                            {{-- <td>{!! Sales::user($product->created_by)->name !!}</td> --}}
                            <td></td>
                            <td></td>
                            <td>@if ($product->is_approved == "No")
                              Pending approval
                               
                            @endif</td>
                            <td>{!! date('F jS, Y', strtotime($product->created_at)) !!}</td>
                            <td>
                               <a href="{!! route('inventory.approve',$product->sku_code) !!}" class="btn btn-sm btn-success">view</a>
                            </td>
                         </tr>
                      @endforeach
                   </tbody>
                </table>
             </div>
             <div class="mt-1">
                {{-- {{ $products->links() }} --}}
             </div>
          </div>
       </div>
 
       <!-- Modal -->
       
    </div>
 </div>
 