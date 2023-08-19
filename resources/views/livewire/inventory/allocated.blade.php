<div>
   <div class="row">
      <div class="col-md-12 mb-1">

      </div>
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <table class="table table-bordered table-striped">
                  <thead>
                     <th>#</th>
                     <th>Sales Person</th>
                     {{-- <th>Total Items</th> --}}
                     <th>Date Allocated</th>
                     <th>Allocated by</th>
                     <th>Status</th>
                     <th>Action</th>
                  </thead>
                  <tbody>
                  @forelse ($allocations as $count => $allocation)
                     <tr>
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $allocation->name !!}</td>
                        {{-- <td>{!! Sales::total_allocated_items($allocation->allocation_code)->sum('current_qty') !!}</td> --}}
                        <td>{!! date('F jS, Y', strtotime($allocation->created_at)) !!}</td>
                        <td>{!! Sales::user($allocation->created_by)->name !!}</td>
                        <td><span class="badge bg-secondary">{!! $allocation->status !!}</span></td>
                        <td>
                              <a href="{!! route('inventory.allocate.items', $allocation->allocation_code) !!}" class="btn btn-sm btn-success">view</a>
                        </td>
                     </tr>
                  @empty
                     <tr>
                        <td colspan="6" class="text-center">No allocations found.</td>
                     </tr>
                  @endforelse
                  </tbody>
               </table>
            </div>
            <div class="mt-1">
               {{ $allocations->links() }}
            </div>
         </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="allocation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <form class="modal-content" method="Post" action="{!! route('inventory.allocate.user') !!}">
               @csrf
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Choose sales person</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label for="">Sales Person</label>
                     {!! Form::select('sales_person',$salesPerson,null,['class'=>'form-control','required'=>'']) !!}
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-success">Save information</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
