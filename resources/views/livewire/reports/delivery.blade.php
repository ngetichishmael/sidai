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
                       <th>User Name</th>
                       <th>User Type</th>
                       <th>Action</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($deliveries as $delivery)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $delivery->order_code }}</td>
                     <td>{{ $delivery->Customer->customer_name??'' }}</td>
                     <td>{{ $delivery->User->name??'' }}</td>
                     <td>{{ $delivery->User->account_type??'' }}</td>
                     <td><a href="{{ URL('orders/deliveryitems/'.$delivery->order_code) }}" class="btn" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>