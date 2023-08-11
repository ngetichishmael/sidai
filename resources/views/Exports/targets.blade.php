<table>
    <thead>
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>User Type</th>
            <th>Lead Target</th>
            <th>Lead Achieved</th>
            <th>Sales Target</th>
            <th>Sales Achieved</th>
            <th>Visit Target</th>
            <th>Visit Achieved</th>
            <th>Order Target</th>
            <th>Order Achieved</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($targets as $key => $target)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $target->user_name }}</td>
                <td>{{ $target->user_type }}</td>
                <td>{{ $target->leads_target }}</td>
                <td>{{ $target->leads_achieved }}</td>
                <td>{{ $target->sales_target }}</td>
                <td>{{ $target->sales_achieved }}</td>
                <td>{{ $target->visits_target }}</td>
                <td>{{ $target->visits_achieved }}</td>
                <td>{{ $target->orders_target }}</td>
                <td>{{ $target->orders_achieved }}</td>
            </tr>
        @endforeach




    </tbody>
 </table>
 