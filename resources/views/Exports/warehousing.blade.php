<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Warehouse Name</th>
        <th>Shop Attendees</th>
        <th>Region</th>
        <th>Subregion</th>
        <th>Quantity</th>
        {{-- <th>No of Allocations</th> --}}
        <th>Last Re-stock</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($warehouses as $key=> $warehouse)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $warehouse->name }}</td>
            <td>{{ $warehouse->Manager->name ?? '' }}</td>
            <td>{{ $warehouse->region->name??'' }}</td>
            <td>{{ $warehouse->subregion->name??'' }}</td>
            <td>{{ $warehouse->products_count }}</td>
            {{-- <td></td> --}}
            <td>{{ $warehouse->updated_at->format('d/m/Y') }}</td>
            
        </tr>
    @endforeach
    </tbody>
 </table>
 