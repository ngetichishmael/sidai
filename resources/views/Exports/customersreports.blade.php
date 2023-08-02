<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Customer Name</th>
        <th>Orders</th>
        <th>Region</th>
        <th>Subregion</th>
        
    </tr>
    </thead>
    <tbody>
        @foreach ($customers as $key => $customer )
                    <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->orders_count }}</td>
                    <td>{{ $customer->Region->name??'' }}</td>
                    <td>{{ $customer->Subregion->name??'' }}</td>
                    
                </tr>  
        @endforeach
    </tbody>
 </table>
 