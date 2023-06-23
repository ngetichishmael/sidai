<div class="row">
    <div class="card">
       <div class="pt-0 pb-2 d-flex justify-content-end align-items-center mx-50 row">
           <div class="col-md-4">
               <div class="form-group">
                   <label for="validationTooltip01">From:</label>
                   <input wire:model="start" name="startDate" type="date" class="form-control"
                       id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
               </div>
           </div>
           <div class="col-md-4">
               <div class="form-group">
                   <label for="validationTooltip01">To:</label>
                   <input wire:model="end" name="startDate" type="date" class="form-control"
                       id="validationTooltip01" placeholder="YYYY-MM-DD HH:MM" required />
               </div>
           </div>
       </div>
       </div>
       @include('partials.stickymenu')
       
<div class="col-md-8">
<div class="card card-default">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Quantity Of Inventory</th>
                    <th>Action</th>
                 </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $key => $supplier )
                <tr>
                   <td>{{ $key+1 }}</td>
                   <td>{{ $supplier->Customer->customer_name }}</td>
                   <td></td>
                   <td><a href="" class="btn btn-sm" style="background-color: rgb(173, 37, 37);color:white">View</a></td>
               </tr>
               
                @endforeach
               
                  
               </tbody>
        </table>

        {{-- <div class="mt-1">{!! $suppliers->links() !!}</div> --}}
    </div>
</div>
</div>