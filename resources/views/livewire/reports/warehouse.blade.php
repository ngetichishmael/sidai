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
   </div>