<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Region</th>
        <th>No of Orders</th>
        <th>No of Customers</th>
        <th>No of Deliveries</th>
    </tr>
    </thead>
    <tbody>
    @foreach($regions as $key=> $region)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $region->name }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
 </table>
 