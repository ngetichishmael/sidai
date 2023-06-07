<div>
   <div class="mb-2 row">
      <div class="col-md-9">
         <label for="">Search</label>
         <input wire:model.debounce.300ms="search" type="text" class="form-control"
                placeholder="Enter name, email or phone number">
      </div>


      <div class="col-md-3">
         <label for="">Items Per</label>
         <select wire:model="perPage" class="form-control">`
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
         </select>
      </div>
   </div>
   <div class="card card-default">
      <div class="card-body">
         <div class="pt-0 card-datatable table-responsive">
            <table class="table table-striped">
               <thead>
               <tr>
                  <th width="1%">#</th>
                  <th>Region</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
                  <th width="12%">Actions</th>
               </tr>
               </thead>
               <tbody>
               @foreach ($manager as $key => $user)
                  <tr>
                     <td>{!! $key + 1 !!}</td>
                     <td>{!! $user->Region->name ?? ' ' !!}</td>
                     <td>{!! $user->name !!}</td>
                     <td>
                        {!! $user->email !!}
                     </td>
                     <td>{!! $user->phone_number !!}</td>
                     <td>{!! $user->status !!}</td>
                     <td>
                        <div class="dropdown" >
                           <button style="background-color: #B6121B;color:white" class="btn btn-md dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                              <i data-feather="settings"></i>
                           </button>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a href="{{ route('user.edit', $user->user_code) }}" type="button" class="dropdown-item btn btn-sm" style="color: #6df16d;font-weight: bold"><i data-feather="edit"></i> &nbsp;Edit</a>
                              <a href="{{ route('user.edit', $user->user_code) }}" type="button" class="dropdown-item btn btn-sm" style="color: #7cc7e0; font-weight: bold"><i data-feather="eye"></i>&nbsp; View</a>
                              {{--                                         <a href="{{ route('order.target.destroy', $order->id) }}" type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f; font-weight: bold"><i data-feather="delete"> </i> &nbsp; Delete</a>--}}


                              @if ($user->status === 'Active')
                                 <a wire:click.prevent="deactivate({{ $user->id }})"
                                    onclick="confirm('Are you sure you want to DEACTIVATE this user?')||event.stopImmediatePropagation()"
                                    type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f;font-weight: bold" ><i data-feather="pause"></i>&nbsp;Suspend</a>
                              @else
                                 <a wire:click.prevent="activate({{ $user->id }})"
                                    onclick="confirm('Are you sure you want to ACTIVATE this user?')||event.stopImmediatePropagation()"
                                    type="button" class="dropdown-item btn btn-sm me-2" style="color:  #54a149; font-weight: bold"><i data-feather="check"></i>&nbsp;Activate </a>
                              @endif
                           </div>
                        </div>
                     </td>
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
         <div class="mt-1">{!! $manager->links() !!}</div>
      </div>
   </div>
</div>
