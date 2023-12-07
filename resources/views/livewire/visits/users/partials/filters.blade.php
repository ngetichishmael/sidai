<div class="card">
   <div class="card-header">
      Filters
   </div>
   <div class="card-body">
      <div class="row">
         <!-- Search Field -->
         <div class="col-md-4">
            <input wire:model="search" type="text" class="form-control" placeholder="Search">
         </div>

         <!-- Pagination Per Page -->
         <div class="col-md-3">
            <select wire:model="perPage" class="form-control">
               <option value="10">10 per page</option>
               <option value="20">20 per page</option>
               <option value="50">50 per page</option>
            </select>
         </div>

         <!-- Date Range Filters -->
         <div class="col-md-5 d-flex">
            <input wire:model="start" type="date" class="form-control mx-2">
            <input wire:model="end" type="date" class="form-control mx-2">
         </div>
      </div>
   </div>
</div>