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
                       <th>Warehouse Name</th>
                       <th>History</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($warehouses as $warehouse)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $warehouse->name }}</td>
                     <td><a href="{{ route('allproducts.reports',$warehouse->warehouse_code) }}" class="btn btn-sm" style="background-color: brown;color:white">inventory history</a></td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
            </div>
           </div>
        </div>
     </div>
   </div>