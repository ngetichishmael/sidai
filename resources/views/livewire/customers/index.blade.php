<div>
    <div class="row mb-1">
        <div class="col-md-10">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name, email address or phonenumber">
        </div>
        <div class="col-md-2">
            <label for="">Items Per page</label>
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
            <table class="table table-striped table-bordered">
                <thead>
                    <th width="1%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phonenumber</th>
                    <th>Address</th>
                    <th width="15%">Action</th>
                </thead>
                <tbody>
                    @foreach ($contacts as $contact)
{{--                        @if ($contact->region_id == Auth::user()->businessID)--}}
                            <tr {{-- class="success" --}}>
                                <td>{!! $count + 1 !!}</td>
                                <td>
                                    {!! $contact->customer_name !!}
                                </td>
                                <td>{!! $contact->customer_email !!}</td>
                                <td>{!! $contact->phone_number !!}</td>
                                <td>
                                    {!! $contact->address !!}
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-warning">View</a>
                                    <a href="{{ route('customer.edit', $contact->customerID) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                </td>
                            </tr>
{{--                        @endif--}}
                    @endforeach
                </tbody>
            </table>
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@section('scripts')
@endsection
