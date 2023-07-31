<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Order ID</th>
        <th>Customer Name</th>
        <th>Sales Rep</th>
        <th>No. of Products</th>
        <th>Region</th>
        <th>Sub Region</th>
        <th>Status</th>
        <th>Created Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($preorders as $key=> $preorder)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $preorder->order_code }}</td>
            <td>{{ $preorder->Customer->customer_name??'N/A' }}</td>
            <td>{{ $preorder->User->name??'N/A' }}</td>
            <td>{{ $preorder->order_items_count }}</td>
            <td>{{ $preorder->User->Region->name??'N/A' }}</td>
            <td>{{ $preorder->Customer->Area->Subregion->name ?? 'N/A' }}</td>
            <td>{{ $preorder->order_status ??'N/A' }}</td>
            <td>{{ $preorder->created_at->format('d/m/Y')??'' }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 