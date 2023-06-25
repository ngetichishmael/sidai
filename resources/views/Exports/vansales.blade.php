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
    @foreach($vansales as $key=> $vansale)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $vansale->order_code }}</td>
            <td>{{ $vansale->Customer->customer_name??'N/A' }}</td>
            <td>{{ $vansale->User->name??'N/A' }}</td>
            <td>{{ $vansale->User->account_type??'N/A' }}</td>
            <td>{{ $vansale->order_status ??'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 