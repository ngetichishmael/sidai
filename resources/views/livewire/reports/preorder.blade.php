<div class="row">
    <div class="col-md-3">
        <label for="">Filter By</label>
        <select wire:model="" class="form-control">`
            <option value="" selected>select</option>
            <option value=""></option>

        </select>
    </div>
    <div class="col-md-3">
        <label for="">Filter By</label>
        <select wire:model="" class="form-control">`
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
                                    <th>Sales Rep</th>
                                    <th>Region</th>
                                    <th>Sub Region</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preorders as $preorder)
                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $preorder->order_code }}</td>
                                        <td>{{ $preorder->Customer->customer_name ?? '' }}</td>
                                        <td>{{ $preorder->User->name ?? '' }}</td>
                                        <td>{{ $preorder->User->Region->name ?? '' }}</td>
                                        <td>{{ $preorder->User->Subregion->name ?? '' }}</td>
                                        <td>{{ $preorder->order_status ?? '' }}</td>
                                        <td>{{ $preorder->created_at->format('d/m/Y') ?? '' }}</td>
                                        <td><a href="{{ URL('orders/items/' . $preorder->order_code) }}"
                                                class="btn btn-sm"
                                                style="background-color: rgb(173, 37, 37);color:white">View</a></td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
