<div class="row">
    <div class="col-md-3">
        <label for="validationTooltip01">Start Date</label>
        <input wire:model="start" name="start" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="validationTooltip01">End Date</label>
        <input wire:model="end" name="startDate" type="date" class="form-control" id="validationTooltip01"
            placeholder="YYYY-MM-DD HH:MM" required />
    </div>
    <div class="col-md-3">
        <label for="">User Category</label>
        <select wire:model="" class="form-control" disabled>`
            <option value="" selected>select</option>
            <option value=""></option>

        </select>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-icon btn-outline-success" wire:click="export" wire:loading.attr="disabled"
            data-toggle="tooltip" data-placement="top" title="Export Excel">
            <img src="{{ asset('assets/img/excel.png') }}"alt="Export Excel" width="20" height="20"
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
        </select>
    </div>
    <div class="col-md-3">
        <label for="">Search by name, route, region</label>
        <input type="text" wire:model="search" class="form-control"
            placeholder="Enter customer name, email address or phone number">
    </div>
</div>
<br>
<div class="row">
    @include('partials.stickymenu')
    <div class="col-md-8">
        <div class="card card-inverse">
            <div class="card-body">
                <div class="card-datatable table-responsive">
                    <table id="data-table-default" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order Code</th>
                                <th>Customer Name</th>
                                <th>Amount</th>
                                <th>Customer Category</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($orders as $key => $order)
                                @if ($order->id == null)
                                    <tr>
                                        <td colspan="7" class="text-center ql-color-red">No data</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $order->order_code }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->total_payment ?? 0 }}</td>
                                        <td>{{ $order->customer_type }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td><a href="{{ route('paymentsdetails.reports', [
                                            'id' => $order->id,
                                        ]) }}"
                                                class="btn btn-sm"
                                                style="background-color: #B6121B;color:white">View</a>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7">No data</td>
                                </tr>
                            @endforelse



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
