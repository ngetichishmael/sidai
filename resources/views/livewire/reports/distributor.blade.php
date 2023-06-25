<div class="row">
      <div class="col-md-3">
         <label for="">Filter By</label>
         <select wire:model="" class="form-control">`
            <option value="" selected>select</option>
            <option value=""></option>
            
         </select>
      </div>
      <div class="col-md-3">
         <label for="">Filter By</label>
         <select wire:model="" class="form-control">`
            <option value="" selected>select</option>
            <option value=""></option>
            
         </select>
      </div>
      <div class="col-md-3">
         <button type="button" class="btn btn-icon btn-outline-success" wire:click="export"
             wire:loading.attr="disabled" data-toggle="tooltip" data-placement="top" title="Export Excel">
             <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
                 data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
         </button>
     </div>
   </div>
   <br>
<div class="row">
   @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-inverse">
           <div class="card-body">
            <div class="card-datatable table-responsive">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Name of Distributor</th>
                       <th>Number of Orders</th>
                       <th>Number of Assigned Orders</th>
                       <th>Region</th>
                       <th>Route</th>
                    </tr>
                 </thead>
                 <tbody>
                    @foreach ($distributors as $distributor)
                    <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $distributor->Customer->customer_name }}</td>
                     <td></td>
                     <td></td>
                     <td>{{ $distributor->Customer->Region->name??'' }}</td>
                     <td>{{ $distributor->Customer->Route->name??'' }}</td>
                 </tr> 
                    @endforeach
                 </tbody>
              </table>
            </div>
           </div>
        </div>
     </div>
   </div>