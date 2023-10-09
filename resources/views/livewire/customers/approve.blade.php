<div>
    <div class="mb-1 row">
        <div class="col-md-10">
            <label for="">Search by name, route, region</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name, email address or phone number">
        </div>
        <div class="col-md-2">
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
                    <th>Phone Number</th>
                    <th>Region</th>
                    <th>Sub-region</th>
                    <th>Route</th>
                    {{-- <th>Customer Type</th> --}}
                    <th>Created By</th>
                    <th>Date</th>
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
                            {{-- <td>{!! $contact->customer_type !!}</td> --}}
                            <td>
                                {!! $this->Creator($contact->id) ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at->format('d/m/Y') ?? '' !!}
                            </td>
                            <td>
                                @if ($contact->creditor_approved === 0 || $contact->creditor_approved === 2 || @empty($contact->creditor_approved) )
                                    <button wire:click.prevent="approveCustomer({{ $contact->id }})"
                                        onclick="confirm('Are you sure you want to APPROVE this Customer?')||event.stopImmediatePropagation()"
                                        type="button" class="btn btn-success btn-sm">Approve</button>
                               @elseif ($contact->creditor_approved === 1)
                                    <button wire:click.prevent="disapproveCustomer({{ $contact->id }})"
                                        onclick="confirm('Are you sure you want to DISAPPROVE this Customer?')||event.stopImmediatePropagation()"
                                        type="button" class="btn btn-danger btn-sm">Disapprove</button>
                                @endif
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

                <div class="mt-1">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
