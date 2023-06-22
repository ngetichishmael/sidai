<div class="row">
   <div class="col-md-3">
      <label for="">Filter By</label>
      <select wire:model="" class="form-control">`
          <option value="" selected>select</option>
          <option value=""></option>
         
      </select>
   </div>
   </div>
   <br>
<div class="row">
    <div class="col-md-12">
        <div class="card card-inverse">
           <div class="card-body">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Order ID</th>
                       <th>Customer Name</th>
                       <th>Sales Rep</th>
                       <th>Region</th>
                       <th>Sub Region</th>
                       <th>Status</th>
                       <th>Created Date</th>
                       <th>Action</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($preorders as $preorder)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $preorder->order_code }}</td>
                        <td>{{ $preorder->Customer->customer_name??'' }}</td>
                        <td>{{ $preorder->User->name??'' }}</td>
                        <td>{{ $preorder->User->Region->name??'' }}</td>
                        <td>{{ $preorder->User->Subregion->name??'' }}</td>
                        <td>{{ $preorder->order_status ??'' }}</td>
                        <td>{{ $preorder->created_at->format('d/m/Y')??'' }}</td>
                        <td><a href="{{ URL('orders/items/'.$preorder->order_code) }}" class="btn btn-sm" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                    </tr>
                        
                    @endforeach
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>