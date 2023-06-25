<div class="row">
   <div class="col-md-3">
       <label for="validationTooltip01">Start Date</label>
       <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="validationTooltip01">End Date</label>
       <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="">User Category</label>
       <select wire:model="" class="form-control">`
           <option value="" selected>select</option>
           <option value=""></option>

       </select>
   </div>
   <div class="col-md-3">
       <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
           data-toggle="tooltip" data-placement="top" title="Export Excel">
           <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
               data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
       </button>
   </div>
</div>
<div class="row">
   <div class="col-md-3">
       <label for="">Status</label>
       <select wire:model="" class="form-control">`
           <option value="" selected>select</option>
           <option value=""></option>

       </select>
   </div>
</div>
   <br>
<div class="row">
   @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-inverse">
           <div class="card-body">
            <div class="d-flex flex-row flex-nowrap overflow-auto">
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
                  @foreach ($vansales as $vansale)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $vansale->order_code }}</td>
                     <td>{{ $vansale->Customer->customer_name??'' }}</td>
                     <td>{{ $vansale->User->name??'' }}</td>
                     <td>{{ $vansale->User->account_type??'' }}</td>
                     <td><a href="{{ URL('orders/vansaleitems/'.$vansale->order_code) }}" class="btn" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
            </div>
           </div>
        </div>
     </div>
   </div>