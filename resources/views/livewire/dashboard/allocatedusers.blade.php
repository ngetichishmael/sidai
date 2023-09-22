<div>
   <div class="card">
      <h5 class="card-header"></h5>
      <div class="pt-0 pb-2 d-flex justify-content-between align-items-center mx-50 row">
         <div class="col-md-4 user_role">
            <div class="input-group input-group-merge">
               <div class="input-group-prepend">
                  <span class="input-group-text"><i data-feather="search"></i></span>
               </div>
               <input wire:model="search" type="text" id="fname-icon" class="form-control" name="fname-icon"
                      placeholder="Search" />
            </div>
         </div>
         <div class="col-md-2 user_role">
            <div class="form-group">
               <label for="selectSmall">Per Page</label>
               <select wire:model="perPage" class="form-control form-control-sm" id="selectSmall">
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
               </select>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-body">
                     <table class="table table-bordered table-striped">
                        <thead>
                        {{--                  <th>#</th>--}}
                        <th>Sales Person</th>
                        {{-- <th>Total Items</th> --}}
                        <th>Date Allocated</th>
                        <th>Allocated by</th>
{{--                        <th>Status</th>--}}
                        <th>Action</th>
                        </thead>
                        <tbody>
                        @forelse ($allocations as $count => $allocation)
                           <tr>
                              {{--                        <td>{!! $count + 1 !!}</td>--}}
                              <td>{!! $allocation->name !!}</td>
                              {{-- <td>{!! Sales::total_allocated_items($allocation->allocation_code)->sum('current_qty') !!}</td> --}}
                              <td>{!! date('F jS, Y', strtotime($allocation->created_at)) !!}</td>
                              <td>{!! Sales::user($allocation->created_by)->name !!}</td>
{{--                              <td><span class="badge bg-secondary">{!! $allocation->status !!}</span></td>--}}
                              <td>
                                 <a href="{!! route('inventory.allocate.items', $allocation->allocation_code) !!}" class="btn btn-sm btn-success">view Items</a>
                              </td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="6" class="text-center">No stock holdings found.</td>
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
   </div></div>
