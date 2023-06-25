<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>User Name</th>
        <th>User Type</th>
        <th>Status</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($deliveries as $key=> $delivery)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $delivery->order_code }}</td>
            <td>{{ $delivery->Customer->customer_name??'N/A' }}</td>
            <td>{{ $delivery->User->name??'N/A' }}</td>
            <td>{{ $delivery->User->account_type??'N/A' }}</td>
            <td>{{ $delivery->order_status ??'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 