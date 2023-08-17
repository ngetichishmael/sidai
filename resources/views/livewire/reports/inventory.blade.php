<div class="row">
   <div class="col-md-3">
       <label for="validationTooltip01">Start Date</label>
       <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="validationTooltip01">End Date</label>
       <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="">User Category</label>
       <select wire:model="" class="form-control" disabled>`
           <option value="" selected>select</option>
           <option value=""></option>

       </select>
   </div>
   <div class="col-md-3">
       <button type="button" class="btn btn-icon btn-outline-success" wire:click="" wire:loading.attr="disabled"
           data-toggle="tooltip" data-placement="top" title="Export Excel">
           <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
               data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
       </button>
   </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <label for="">Status</label>
        <select wire:model="status" class="form-control">
            <option value="" selected>select</option>
            <option value=""></option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
</div>
<br>
@include('partials.stickymenu')
<br>
<div class="row">
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