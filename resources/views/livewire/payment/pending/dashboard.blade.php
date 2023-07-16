<div>
    <div class="mb-1 row">
        <div class="col-md-10">
            <label for="">Search</label>
            <input type="text" wire:model="search" class="form-control"
                placeholder="Enter customer name,order number or sales person">
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
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Creditors</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        <th>Pending Balance</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deliveries as $count => $deliver)
                        <tr>
                            <td>{{ $count + 1 }}</td>
                            <td>{{ $deliver->Customer->customer_name ?? ' ' }}</td>
                            {{-- <td>{{ $deliver->User->name ?? '' }}</td> --}}
                            <td>{{ number_format($deliver->Order->price_total ?? 0) }}</td>
                            <td>{{ number_format($deliver->Order->price_total ?? 0) }}</td>
                            <td>{{ number_format($deliver->Order->balance ?? 0) }}</td>
                            {{-- <td>{{ $deliver->delivery_status }}</td> --}}
                            <td>@if ($deliver->Order->balance === 0)
                                Complete
                                @elseif ($deliver->Order->balance >1)
                                Pending
                                @else
                                Overpaid
                                
                            @endif
                            </td>
                            <td>{{ $deliver->updated_at }}</td>
                            <td>
                                <a href="{!! route('delivery.details', $deliver->order_code, $deliver->name) !!}" class="btn btn-sm"
                                    style="background-color: #B6121B;color:white">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-1">{{ $deliveries->links() }}</div>
        </div>
    </div>

</div>
