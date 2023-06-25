<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Name of Distributor</th>
        <th>Number of Orders</th>
        <th>Number of Assigned Orders</th>
        <th>Region</th>
        <th>Route</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($distributors as $key=> $distributor)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $distributor->Customer->customer_name }}</td>
            <td></td>
            <td></td>
            <td>{{ $distributor->Customer->Region->name??'' }}</td>
            <td>{{ $distributor->Customer->Route->name??'' }}</td>
        </tr>
    @endforeach
    </tbody>
 </table>
 