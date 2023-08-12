<div>
    <div class="mb-1 row">
        <div class="col-md-3">
            <label for="">Filter By Region</label>
            <select wire:model="regional" class="form-control">`
                <option value="" selected>select</option>
                @foreach ($regions as $region)
                    <option value="{{ $region->name }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="">Flter By Group</label>
            <select wire:model="group" class="form-control">
                <option value="" selected>select</option>
                @foreach ($groups as $group)
                    <option value="{{ $group->group_name }}">{{ $group->group_name }}</option>
                @endforeach

            </select>
        </div>
        <div class="col-md-3">
            <label for="">Search by name, route, region</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name, email address or phone number">
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
                            <td>{!! $contact->customer_number !!}</td>

                            <td class="cell-fit">
                                {!! $contact->region_name ?? ($contact->Region->name ?? '') !!}
                            </td>
                            <td class="cell-fit">{!! $contact->subregion_name ?? '' !!}</td>
                            {{--                            <td class="cell-fit">{!! $contact->Area->name ?? '' !!}</td> --}}
                            <td class="cell-fit">{!! $contact->area_name ?? '' !!}</td>
                            
                            <td>
                                {!! $this->Creator($contact->id) ?? '' !!}
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
                                        <i data-feather="settings"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="{{ route('customer.edit', $contact->id) }}" type="button"
                                            class="dropdown-item btn btn-sm" style="color:#7cc7e0 ;font-weight: bold"><i
                                                data-feather="edit"></i> &nbsp;Edit</a>
                                        <a href="{{ route('creditor.details', $contact->id) }}" type="button"
                                            class="dropdown-item btn btn-sm"
                                            style="color:#6df16d ; font-weight: bold"><i data-feather="eye"></i>&nbsp;
                                            View</a>
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
