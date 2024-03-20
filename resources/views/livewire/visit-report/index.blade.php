<div>
   <div class="row mb-1">
      <div class="col-md-6">
         <label for="">Search by name, description, type, status</label>
         <input type="text" wire:model="search" class="form-control"
                placeholder="Enter name, description, type, status">
      </div>
   <div class="col-md-3">
      <label for="validationTooltip01">Start Date</label>
      <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip01"
             placeholder="YYYY-MM-DD HH:MM" required />
   </div>
   <div class="col-md-3">
      <label for="validationTooltip01">End Date</label>
      <input wire:model="end" name="end" type="date" class="form-control" id="validationTooltip01"
             placeholder="YYYY-MM-DD HH:MM" required />
   </div>

   </div>
   <div class="card card-default">
      <div class="card-body">
         <div class="card-datatable table-responsive">
            <table class="table table-striped table-bordered">
               <thead>
               <tr>
                  <th>#</th>
                  <th>Form Name</th>
                  <th>Form Type</th>
                  <th>Form Fields</th>
                  <th>Action</th>
               </tr>
               </thead>
               <tbody>
               @foreach ($forms as $key => $form)
                  <tr>
                     <td>{{ $key + 1 }}</td>
                     <td>{{ $form->name ?? '' }}</td>
                     <td>{{ $form->type ?? '' }}</td>
                     <td>
                        <table class="table">
                           <thead>
                           <tr>
                              <th>Field Name</th>
                              <th>Type</th>
                              <th>Required</th>
                           </tr>
                           </thead>
                           <tbody>
                           @foreach ($form->fields as $field)
                              @if(isset($field['name']))
                                 <tr>
                                    <td>{{ $field['name'] }}</td>
                                    <td>{{ isset($field['type']) ? $field['type'] : 'N/A' }}</td>
                                    <td>{{ isset($field['required']) && $field['required'] ? 'Yes' : 'No' }}</td>
                                 </tr>
                              @endif
                           @endforeach
                           </tbody>
                        </table>
                     </td>
                     <td><a href="#" class="btn btn-sm" style="background-color: rgb(173, 37, 37);color:white">Responses</a></td>
                  </tr>
               @endforeach
               </tbody>
            </table>

         </div>
      </div>
   </div>
</div>
