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
                        <th>Route</th>
                        <th>Payment Deadline</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
{{--                        <th>Order</th>--}}
                        <th width="15%">Action</th>
{{--                    <th>Status</th>--}}
                    </thead>
                    <tbody>
                        @foreach ($contacts as $count => $contact)
                            <td>{!! $count + 1 !!}</td>
                            <td>
                                {!! $contact->customer_name !!}
                            </td>
{{--                            <td>{!! $contact->phone_number !!}</td>--}}
{{--                            <td>--}}
{{--                                {!! $contact->Area->Subregion->Region->name ?? ' ' !!}--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {!! $contact->Area->Subregion->name ?? '' !!}--}}
{{--                            </td>--}}
                            <td>
                                {!! $contact->Area->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->Creator->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->created_at ?? '' !!}
                            </td>
                            <td>
                                <a href="{{ route('make.orders', ['id' => $contact->id]) }}"
                                    class="btn btn-sm btn-secondary">View</a>
                                <a href="{{ route('creditor.edit', $contact->id) }}"
                                    class="btn btn-sm btn-primary">Clear</a>
                            </td>
{{--                            <td>--}}
{{--                                @if ($contact->approval === 'Approved')--}}
{{--                                    <button wire:click.prevent="deactivate({{ $contact->id }})"--}}
{{--                                        onclick="confirm('Are you sure you want to DEACTIVATE this customer?')||event.stopImmediatePropagation()"--}}
{{--                                        type="button" class="btn btn-success btn-sm">Approved</button>--}}
{{--                                @else--}}
{{--                                    <button wire:click.prevent="activate({{ $contact->id }})"--}}
{{--                                        onclick="confirm('Are you sure you want to ACTIVATE this customer?')||event.stopImmediatePropagation()"--}}
{{--                                        type="button" class="btn btn-danger btn-sm">Pending</button>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-1">
                    {{ $contacts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
