<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Order Code</th>
        <th>Customer Name</th>
        <th>Amount</th>
        <th>Customer Category</th>
        <th>Date</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $key=> $order)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $order->order_code }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->total_payment }}</td>
            <td>{{ $order->customer_type }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 