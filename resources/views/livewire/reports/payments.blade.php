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
                            @foreach ($orders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->total_payment }}</td>
                                    <td>{{ $order->customer_type }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td><a href="{{ route('paymentsdetails.reports', [
                                        'id' => $order->id,
                                    ]) }}"
                                            class="btn btn-sm" style="background-color: #B6121B;color:white">View</a></td>
                                </tr>
                            @endforeach



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
