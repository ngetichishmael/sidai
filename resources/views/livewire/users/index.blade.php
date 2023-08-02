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
       <div class="col-md-3">
          <label for=""></label>
          <div>
             @if ($bulkDisabled)
                <button type="button" class="btn" style="background-color: #B6121B;color:white"
                        data-bs-toggle="modal" data-bs-target="#danger">
                   <span>Notify</span>
                </button>
             @else
                <button type="button" class="btn" style="background-color: #B6121B;color:white"
                        data-bs-toggle="modal" data-bs-target="#success">
                   <span>Notify</span>
                </button>
             @endif
          </div>
       </div>
    </div>
    <div class="card card-default">
        <div class="card-body">
            <div class="pt-0 card-datatable">
                <table class="table table-striped table-bordered zero-configuration table-responsive">
                    <thead>
                        <tr>
                           <th width="1%">#</th>
                           <th width="0.5%"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Region</th>
                            <th>Status</th>
                            <th width="12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{!! $key + 1 !!}</td>
                               <td>
                                  <div class="custom-control custom-control-success custom-checkbox">
                                     <input wire:model="selectedData" value="{{ $user->user_code }}" type="checkbox"
                                            class="custom-control-input" id="colorCheck3{{ $user->user_code }}" />
                                     <label class="custom-control-label"
                                            for="colorCheck3{{ $user->user_code }}"></label>
                                  </div>
                               </td>
                                <td>{!! $user->name !!}</td>
                                <td> {!! $user->email !!}</td>
                                <td>{!! $user->phone_number !!}</td>
                               <td>{!! $user->Region->name ?? '' !!}</td>
                                <td>{!! $user->status !!}</td>
                                <td>
                                   <div class="dropdown" >
                                      <button style="background-color: #B6121B;color:white" class="btn btn-md dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                         <i data-feather="settings"></i>
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                         <a href="{{ route('user.edit', $user->user_code) }}" type="button" class="dropdown-item btn btn-sm" style="color: #6df16d;">&nbsp;Edit</a>
                                         <a href="{{ route('user.view', $user->user_code) }}" type="button" class="dropdown-item btn btn-sm" style="color: #7cc7e0;"> View</a>
{{--                                         <a href="{{ route('order.target.destroy', $order->id) }}" type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f; font-weight: bold"><i data-feather="delete"> </i> &nbsp; Delete</a>--}}
                                    @if ($user->status === 'Active')
                                        <a wire:click.prevent="deactivate({{ $user->id }})"
                                            onclick="confirm('Are you sure you want to DEACTIVATE this user?')||event.stopImmediatePropagation()"
                                            type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f;font-weight: bold" >&nbsp;Suspend</a>
                                    @else
                                        <a wire:click.prevent="activate({{ $user->id }})"
                                            onclick="confirm('Are you sure you want to ACTIVATE this user?')||event.stopImmediatePropagation()"
                                            type="button" class="dropdown-item btn btn-sm me-2" style="color:  #54a149; font-weight: bold">&nbsp;Activate </a>
                                    @endif
                                      </div>
                                   </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-1">{!! $users->links() !!}</div>
        </div>
    </div>
   @include('livewire.notification.users.modal')
</div>
