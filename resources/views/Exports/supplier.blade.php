<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Quantity Of Inventory</th>
        
        
    </tr>
    </thead>
    <tbody>
    @foreach($suppliers as $key=> $supplier)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->orders_count }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 