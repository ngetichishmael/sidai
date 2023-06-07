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
                      <th>Sales Name</th>
                      <th>Status</th>
                      <th>Created On</th>
                      <th>Action</th>
                   </thead>
                   <tbody>
                      @foreach($products as $count=>$product)
                         <tr>
                            <td>{!! $count+1 !!}</td>
                            <td>{!! $product->User->name ?? '' !!}</td>
                            <td>{!! $product->status !!}</td>
                            <td>{!! date('F jS, Y', strtotime($product->created_at)) !!}</td>
                            <td>
                               <a href="{!! route('inventory.approve',$product->id) !!}" class="btn btn-sm" style="background-color: #B6121B;color:white">view</a>
                            </td>
                         </tr>
                      @endforeach
                   </tbody>
                </table>
             </div>
             <div class="mt-1">
                {{ $products->links() }}
             </div>
          </div>
       </div>

       <!-- Modal -->

    </div>
 </div>
