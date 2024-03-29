<div>
    <div class="mb-1 row">
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
                        <th>Orders</th>
                        <th>Un-Settled Orders</th>
                        <th>Balance</th>
                        <th>Region</th>
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
                            <td>{{ $this->OrdersCount($contact->id) }}</td>
                            <td>{{ $this->notSettledOrders($contact->id)['count'] }}</td>
                            <td>{{ $this->notSettledOrders($contact->id)['sum'] }}</td>


                            <td class="cell-fit">
                                {!! $contact->region_name ?? ($contact->Region->name ?? '') !!}
                            </td>
                            {{-- <td class="cell-fit">{!! $contact->subregion_name ?? '' !!}</td> --}}
                            <td class="cell-fit">{!! $contact->area_name ?? '' !!}</td>
                            <td>
                                {!! $this->Creator($contact->id) ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at->format('d/m/Y') ?? '' !!}
                            </td>
                            <td>
                                <a href="{{ route('creditor.info', $contact->id) }}"
                                    class="btn btn-sm" style="background-color: #B6121B;color:white">View</a>
                                <a href="{{ route('creditor.edit', $contact->id) }}"
                                    class="btn btn-sm" style="background-color: #B6121B; color:white">Edit</a>
                            </td>
                            </tr>
                        @empty
                            <div>
                                <tr>
                                    <td colspan="10" class="text-center"> No Creditor(s) Found ...</td>
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
