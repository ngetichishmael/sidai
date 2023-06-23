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
                       <th>Warehouse Name</th>
                       <th>Shop Attendees</th>
                       <th>Region</th>
                       <th>Subregion</th>
                       <th>Quantity</th>
                       <th>No of Allocations</th>
                       <th>Last Re-stock</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($warehouses as $warehouse)
                  <tr>
                     <td>{{ $count++ }}</td>
                     <td>{{ $warehouse->name }}</td>
                     <td></td>
                     <td>{{ $warehouse->region->name??'' }}</td>
                     <td>{{ $warehouse->subregion->name??'' }}</td>
                     <td>{{ $warehouse->product_information_count }}</td>
                     <td></td>
                     <td>{{ $warehouse->updated_at->format('d/m/Y') }}</td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
           </div>
        </div>
     </div>
   </div>