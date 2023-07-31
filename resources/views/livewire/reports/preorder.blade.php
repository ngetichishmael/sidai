<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="end" name="endDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="">User Category</label>
        <select wire:model="userCategory" class="form-control">
            <option value="" selected>select</option>
            <option value=""></option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
            data-toggle="tooltip" data-placement="top" title="Export Excel">
            <img src="{{ asset('assets/img/excel.png') }}" alt="Export Excel" width="20" height="20"
                data-toggle="tooltip" data-placement="top" title="Export Excel">Export to Excel
        </button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <label for="">Status</label>
        <select wire:model="status" class="form-control">
            <option value="" selected>select</option>
            <option value=""></option>
            <option value="">Pending Delivery</option>
            <option value="">Waiting Acceptance</option>
            <option value="">Delivered</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
</div>
<br>
<br>
<div class="row">
    @include('partials.stickymenu')
    <div class="col-md-10">
        <div class="card card-inverse">
            <div class="card-body">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Added by</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                            @forelse ($preorders as $preorder)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $preorder->order_code }}</td>
                                    <td>{{ $preorder->Customer->customer_name ?? '' }}</td>
                                    <td>{{ $preorder->User->name ?? '' }}</td>
                                    <td class="cell-fit">
                                        {{ $preorder->Customer->Area->Subregion->name ?? '' }},
                                        {{ $preorder->User->Region->name ?? '' }}
                                    </td>
                                    <td>{{ $preorder->order_status ?? '' }}</td>
                                    <td>{{ $preorder->created_at->format('d/m/Y') ?? '' }}</td>
                                    <td><a href="{{ URL('orders/items/' . $preorder->order_code) }}" class="btn btn-sm"
                                            style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <x-emptyrow>
                                        7
                                    </x-emptyrow>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-1">{!! $preorders->links() !!}</div>
            </div>
        </div>
    </div>
</div>
