<div>
    <div class="row">
       <div class="col-md-12">
        <div class="pt-0 card-datatable table-responsive">
          <div class="card">
             <div class="card-header"> Allocated Items</div>
             <div class="card-body">
                <table class="table table-bordered table-striped">
                   <thead>
                      <th>#</th>
                      <th>Product Name</th>
                      <th>Brand</th>
                      <th>Details</th>
                      <th>Category</th>
                      <th>Units</th>
                      <th>Batch Code</th>
                      <th>Action</th>
                   </thead>
                   <tbody>
                      @foreach($products as $count=>$product)
                         <tr>
                            <td>{!! $count+1 !!}</td>
                            <td>{!! $product->product_name !!}</td>
                            <td>{!! $product->brand !!}</td>
                            <td>{!! $product->description !!}</td>
                            <td>{!! $product->category !!}</td>
                            <td>{!! $product->units !!}</td>
                            <td>{!! $product->batch_code !!}</td>
                            <td>@if ($product->is_approved == "No")
                                <a href="{{ route('product.approvestock',$product->id) }}" class="btn btn-primary">Approve</a>
                                @elseif ($product->is_approved == "Yes")
                                <a href="" class="btn btn-success">Approved</a>
                                
                            @endif</td>
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
  @if (count($allocatedItems)==0)
  <td>
    <img src="{{ asset("app-assets/images/logo2.jpeg")}}" alt="Invoice Image" srcset="" style="height: 200px; width:500px;">
 </td> 
  @else
  <td>
    <img src="{{ Storage::url('public/'.$allocatedItems[0]->image) }}" alt="Invoice Image" srcset="">
 </td> 
  @endif
 