<div>
   <div class="row">
      <div class="col-md-9">
         <div class="card">
            <div class="card-header"> Allocated Items</div>
            <div class="card-body">
               <table class="table table-bordered table-striped">
                  <thead>
                  <th>#</th>
                  <th>Product</th>
                  <th>Current Qty</th>
                  <th>Allocated Qty</th>
                  <th>Returned Qty</th>
                  </thead>
                  <tbody>
                  @foreach($allocatedItems as $count=>$item)
                     <tr>
                        <td>{!! $count+1 !!}</td>
                        <td>{!! $item->product_name !!}</td>
                        <td>{!! $item->current_qty !!}</td>
                        <td>{!! $item->allocated_qty !!}</td>
                        <td>{!! $item->returned_qty !!}</td>
                     </tr>
                  @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-3 mt-5 pt-3">
         <div class="card">
            @if (count($allocatedItems)==0)
               <td>
                  <img src="{{ asset("app-assets/images/logo2.jpeg")}}" alt="Invoice Image" srcset="" style="height: 200px; width:500px;">
               </td>
            @else
               <td>
                  <img src="{{ Storage::url('public/'.$allocatedItems[0]->image) }}" alt="Invoice Image" srcset="">
               </td>
            @endif
         </div>
      </div>
   </div>
</div>
