<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Role</th>
            <th>Visits</th>
            <th>Leads</th>
            <th>Sales</th>
            <th>Orders</th>
            <th>No of Checkins</th>
            <th>Conversion Rate</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $key => $employee)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->role }}</td>
                <td>{{ $employee->visit_count }}</td>
                <td>{{ $employee->achieved_leads }}</td>
                <td>{{ $employee->achieved_sales }}</td>
                <td>14</td>
                <td>{{ $employee->visit_count }}</td>
                <td>{{ ($employee->achieved_leads/67) * 100 ,2}}%</td>
            </tr>
        @endforeach




    </tbody>
 </table>
 