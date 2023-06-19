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
                    <th>Created By</th>
                    <th>Date</th>
                    <th width="15%">Action</th>
                    </thead>
                    <tbody>
                    @if ($contacts->isEmpty())
                       <tr>
                          <td colspan="9" class="text-center align-middle">No creditors waiting approval found</td>
                       </tr>
                    @else
                        @foreach ($contacts as $count => $contact)
                            <td>{!! $count + 1 !!}</td>
                            <td>
                                {!! $contact->customer_name ?? '' !!}
                            </td>
                            <td>{!! $contact->phone_number ?? ''!!}</td>
                            <td>
                                {!! $contact->Region->name ?? ' ' !!}
                            </td>
                            <td>
                                {!! $contact->Subregion->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->Area->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->user->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at ?? '' !!}
                            </td>
{{--                            <td>--}}
{{--                                <a href="{{ route('make.orders', ['id' => $contact->id]) }}"--}}
{{--                                    class="btn btn-sm btn-secondary">Order</a>--}}
{{--                                <a href="{{ route('creditor.edit', $contact->id) }}"--}}
{{--                                    class="btn btn-sm btn-primary">Edit</a>--}}
{{--                            </td>--}}
                            <td>
                                @if ($contact->creditor_approved === 0 || $contact->creditor_approved === 2 || @empty($contact->creditor_approved) )
                                    <button wire:click.prevent="approveCreditor({{ $contact->id }})"
                                        onclick="confirm('Are you sure you want to approve this customer to be a creditor?')||event.stopImmediatePropagation()"
                                        type="button" class="btn btn-success btn-sm">Approve</button>
                               @elseif ($contact->creditor_approved === 1)
                                    <button wire:click.prevent="activate({{ $contact->id }})"
                                        onclick="confirm('Are you sure you want to disapprove this customer from list of creditors?')||event.stopImmediatePropagation()"
                                        type="button" class="btn btn-danger btn-sm">Disapprove</button>
                                @endif
                            </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <div class="mt-1">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
