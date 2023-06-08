<div>
    <div class="mb-2 row">
       <div class="col-md-6">
          <label for="">Search</label>
          <input wire:model.debounce.300ms="search" type="text" class="form-control" placeholder="Enter name, email or phone number">
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
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#danger">
                   <span>Notify</span>
                </button>
             @else
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#success">
                   <span>Notify</span>
                </button>
             @endif
          </div>
       </div>
    </div>
    <div class="card card-default">
       <div class="card-body">
          <table class="table table-striped table-bordered zero-configuration">
             <thead>
                <tr>
                   <th width="1%">#</th>
                   <th width="0.5%"></th>
                   {{-- <th width="5%">Image</th> --}}
                   <th>Name</th>
                   <th>Email</th>
                   <th>Phone</th>
                   <th>Status</th>
                   <th width="12%">Actions</th>
                </tr>
             </thead>
             <tbody>
                @foreach($shopattendee as $key => $user)
{{--                   @if($user->businessID == Auth::user()->businessID)--}}
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
                         <td>
                            {!! $user->email !!}
                         </td>
                         <td>{!! $user->phone_number !!}</td>
                         <td style="color: {!! $user->status == 'Active' ? 'green' : 'orangered' !!}">{!! $user->status !!}</td>
                         <td>
                            <div class="dropdown" >
                               <button class="btn btn-md btn-primary dropdown-toggle mr-2" type="button" id="dropdownMenuButton" data-bs-trigger="click" aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" data-bs-auto-close="outside">
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
                        <style>
                           .ellipsis-container {
                              position: relative;
                           }
                           .ellipsis-content li{
                              padding: 10px;
                              list-style: none;

                           }
                           .ellipsis-content {
                              display: none;
                              position: absolute;
                              top: 100%;
                              right: 0;
                              z-index: 1;
                              background-color: #fff;
                              box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2);
                              border-radius: 5px;
                              overflow: hidden;
                           }

                           .ellipsis-btn {
                              border: none;
                              background-color: transparent;
                              cursor: pointer;
                              font-size: 20px;
                              line-height: 0.5;
                           }

                           .ellipsis-container:hover .ellipsis-content {
                              display: block;
                           }
                        </style>

                        <script>
                           const ellipsisBtn = document.querySelector('.ellipsis-btn');
                           const ellipsisContent = document.querySelector('.ellipsis-content');

                           ellipsisBtn.addEventListener('click', () => {
                              ellipsisContent.classList.toggle('show');
                           });
                        </script>
                      </tr>
{{--                   @endif--}}
                @endforeach
             </tbody>
          </table>
          <div class="mt-1">{!! $shopattendee->links() !!}</div>
       </div>
    </div>
   @include('livewire.notification.users.modal')
 </div>
