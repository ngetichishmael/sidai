<div class="row">
   <div class="col-md-3">
       <label for="validationTooltip01">Start Date</label>
       <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="validationTooltip01">End Date</label>
       <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
           placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
       <label for="">User Category</label>
       <select wire:model="" class="form-control">`
           <option value="" selected>select</option>
           <option value=""></option>

       </select>
   </div>
        <div class="col-md-3 mb-2">
            <label for="">Export Reports:</label>
            <div class="dropdown">
                <button style="background-color: #B6121B;color:white" class="mr-2 btn btn-md dropdown-toggle"
                    type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true"
                    aria-expanded="false" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="15" height="13">
                    Export
                </button>
                <div class="dropdown-menu dropdown-menu-left">
                    <a class="dropdown-item" wire:click="export">Excel</a>
                    <a class="dropdown-item" wire:click="exportCSV"> CSV</a>
                    <a class="dropdown-item" wire:click="exportPDF">PDF</a>
                </div>
            </div>
        </div>
</div>
<br>
<div class="row">
    
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
    <div class="col-md-12">
        <div class="card card-inverse">
           <div class="card-body">
            <div class="d-flex flex-row flex-nowrap overflow-auto">
              <table id="data-table-default" class="table table-striped table-bordered">
                 <thead>
                    <tr>
                       <th>#</th>
                       <th>Name</th>
                       <th>Role</th>
                       <th>Visits</th>
                       <th>Leads</th>
                       <th>Sales</th>
                       <th>Orders</th>
                       <th>No of Checkins</th>
                       <th>Conversion Rate</th>
                    </tr>
                 </thead>
                 <tbody>
                  @foreach ($employees as $count=> $employee)
                  <tr>
                     <td>{{ $count+1 }}</td>
                     <td>{{ $employee->name }}</td>
                     <td>{{ $employee->role }}</td>
                     <td>{{ $employee->visit_count }}</td>
                     <td>{{ $employee->achieved_leads }}</td>
                     <td>{{ $employee->achieved_sales }}</td>
                     <td>67</td>
                     <td>{{ $employee->visit_count }}</td>
                     <td>{{ ($employee->achieved_leads/67) * 100 ,2}}%</td>
                 </tr>
                  @endforeach
                    
                 </tbody>
              </table>
            </div>
           </div>
        </div>
     </div>
   </div>