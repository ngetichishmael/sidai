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
                    <th width="1%">#</th>
                    <th>OrderID</th>
                    <th>Customer</th>
                    <th>Sales Agents</th>
                    <th>Total Paid</th>
                    <th>Date</th>
{{--                    <th>Approved ?</th>--}}
                    <th>Action</th>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($deliveries as $count => $deliver)
                    <tr>
                        <td>{!! $count + 1 !!}</td>
                        <td>{!! $deliver->order_code !!}</td>
                        <td>{!! $deliver->Customer->customer_name ?? ' ' !!}</td>
                        <td>{!! $deliver->User->name ?? '' !!}</td>
                        <td>{!! number_format(($deliver->Order->price_total ?? 0) - ($deliver->Order->balance ?? 0)) !!}</td>
                        <td>{!! $deliver->updated_at !!}</td>

{{--                        <td>--}}
{{--                            @if ($deliver->approval == 0)--}}
{{--                                <button wire:click.prevent="approve({{ $deliver->id }})"--}}
{{--                                    onclick="confirm('Are you sure you want to APPROVE this order?')||event.stopImmediatePropagation()"--}}
{{--                                    type="button" class="btn btn-danger btn-sm">NO</button>--}}
{{--                            @else--}}
{{--                                <button type="button" class="btn btn-success btn-sm">YES</button>--}}
{{--                            @endif--}}
{{--                        </td>--}}
                        <td><a href="{!! route('delivery.details', $deliver->order_code, $deliver->name) !!}" class="btn btn-sm" style="background-color: #B6121B;color:white">View</a></td>
                    </tr>
                    @endforeach
                    </tr>
                </tbody>
            </table>
            <div class="mt-1">{!! $deliveries->links() !!}</div>
        </div>
    </div>
</div>
