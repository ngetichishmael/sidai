<div>
   <div>
   <div class="row mb-1 mt-1">
      <div class="col-md-3 col-sm-4">
         <label for="">Start Date</label>
         <input wire:model="startDate" type="date" class="form-control">
      </div>
      <div class="col-md-3 col-sm-4">
         <label for="">End Date</label>
         <input wire:model="endDate" type="date" class="form-control">
      </div>
      <div class="col-3"></div>
      <div class="col-md-3 mb-2">
         <label for="">Export Reports:</label>
       <div class="dropdown">
            <button style="background-color: #B6121B;color:white"
                    class="mr-2 btn btn-md dropdown-toggle" type="button" id="dropdownMenuButton"
                    data-bs-trigger="click" aria-haspopup="true" aria-expanded="false"
                    data-bs-toggle="dropdown">
               <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="15" height="13">
               Export
            </button>
            <div class="dropdown-menu dropdown-menu-left">
               <a class="dropdown-item" wire:click="export">Excel</a>
               <a class="dropdown-item"  wire:click="exportCSV"> CSV</a>
               <a class="dropdown-item" wire:click="exportPDF" >PDF</a>
            </div>
         </div>
      </div>
   </div>
    <div class="mb-1 row">
        {{-- <div class="col-md-3">
            <label for="">Filter By Region</label>
            <select wire:model="regional" class="form-control">`
                <option value="" selected>select</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->name }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="col-md-6">
            <label for="">Search by user name, route, region</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name, created by, subregion, town, region">
        </div>
       <div class="col-md-3">
          <label for="">Flter By Group</label>
          <select wire:model="selectedGroup" class="form-control">
             <option value="" selected>select</option>
             @foreach ($groups as $group)
                <option value="{{ $group->name }}">{{ $group->name }}</option>
             @endforeach
          </select>
       </div>
       <div class="col-md-2">
             <label for="">Items Per page</label>
             <select wire:model="perPage" class="form-control">`
                <option value="10" selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="2000">2000</option>
             </select>
          </div>
       </div>
   </div>
    <div class="card card-default">
        <div class="card-body">
            <div class="card-datatable table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th>number</th>
                        <th>Region</th>
                        <th>Subregion</th>
                        <th>Town</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th width="15%">Action</th>
                    </thead>
                    <tbody>
                        @forelse($contacts as $count => $contact)
                            <td>{!! $count + 1 !!}</td>
                            <td>
                                {!! $contact->customer_name !!}
                            </td>
                            <td>{!! $contact->phone_number !!}</td>

                            <td class="cell-fit">
                                {!! $contact->regions->name ?? ($contact->Region->name ?? '') !!}
                            </td>
                            <td>
                               @if ($contact->Area && $contact->Area->Subregion && $contact->Area->Subregion->Region)
                                  {!! $contact->Area->Subregion->Region->name !!}
                                  @if ($contact->Area->Subregion->name)
                                     , <br><i>{!! $contact->Area->Subregion->name !!}</i>
                                  @endif
                               @endif
                            </td>
                            <td>
                               {!! $contact->Area->name ?? '' !!}
                            </td>
                            {{--                            <td class="cell-fit">{!! $contact->Area->name ?? '' !!}</td> --}}
{{--                            <td class="cell-fit">{!! $contact->areas->name ?? '' !!}</td>--}}

                            <td>
                               {!! $contact->Creator->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at->format('d/m/Y') ?? '' !!}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button style="background-color: #B6121B;color:white"
                                        class="mr-2 btn btn-md dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-bs-trigger="click" aria-haspopup="true" aria-expanded="false"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                                        <i data-feather="eye"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"
                                            href="{{ route('customer.edit', $contact->id) }}">
                                            <i data-feather='edit' class="mr-50"></i>
                                            <span>Edit</span>
                                        </a>

                                            <a class="dropdown-item"
                                            href="{{ route('creditor.details', $contact->id) }}">
                                            <i data-feather='eye' class="mr-50"></i>
                                            <span>View</span>
                                        </a>
                                        {{--                                         <a href="{{ route('order.target.destroy', $order->id) }}" type="button" class="dropdown-item btn btn-sm me-2" style="color: #e5602f; font-weight: bold"><i data-feather="delete"> </i> &nbsp; Delete</a> --}}

                                        @if ($contact->approval === 'Approved')
                                            <a wire:click.prevent="deactivate({{ $contact->id }})"
                                                onclick="confirm('Are you sure you want to DEACTIVATE this customer?')||event.stopImmediatePropagation()"
                                                type="button" class="dropdown-item btn btn-sm me-2"
                                                style="color:  #54a149; font-weight: bold"><i
                                                    data-feather="check"></i>&nbsp;>Approved</a>
                                        @else
                                            <a wire:click.prevent="activate({{ $contact->id }})"
                                                onclick="confirm('Are you sure you want to ACTIVATE this customer?')||event.stopImmediatePropagation()"
                                                type="button" class="dropdown-item btn btn-sm me-2"
                                                style="color: #e5602f;font-weight: bold"><i
                                                    data-feather="pause"></i>&nbsp;Pending</a>
                                        @endif
                                    </div>
                                </div>

                            </td>
                            </tr>
                        @empty
                            <div>
                                <tr>
                                    <td colspan="10" class="text-center"> No Customer(s) Found ...</td>
                                </tr>
                            </div>
                        @endforelse
                    </tbody>
                </table>
                @if (!empty($contacts))
                    <div>
                        {{ $contacts->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
