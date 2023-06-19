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
                        <th>Phone Number</th>
                        <th>Route</th>
                        <th>Payment Deadline</th>
                        <th>Total Amount</th>
                        <th>Paid Amount</th>
                        <th width="15%">Action</th>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $count => $contact)
                            <td>{!! $count + 1 !!}</td>
                            <td>
                                {!! $contact->customer_name !!}
                            </td>
                            <td>{!! $contact->phone_number !!}</td>
                            <td>
                                {!! $contact->Area->Subregion->Region->name ?? ' ' !!}
                            </td>
                            <td>
                                {!! $contact->Area->Subregion->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->Area->name ?? '' !!}
                            </td>
                            <td>
                                {!! $contact->Creator->name ?? '' !!}
                            </td>

                            <td>
                                <a href="{{ route('creditors.details', $contact->id) }}"
                                    class="btn btn-sm" style="background-color: #B6121B;color:white">View</a>
                                <a href="{{ route('creditor.edit', $contact->id) }}"
                                    class="btn btn-sm" style="background-color: #B6121B;color:white">Edit</a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

               
            </div>
        </div>
    </div>
</div>
