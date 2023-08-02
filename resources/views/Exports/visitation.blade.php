<table>
    <thead>
        <tr>
            <th>#</th>
            <th>User Name</th>
            <th>User Type</th>
            <th>Visits</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($visitations as $key => $visit)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $visit->name ?? '' }}</td>
                <td>{{ $visit->account_type }}</td>
                <td>{{ $visit->checkings_count??'0' }}</td>
            </tr>
        @endforeach

    </tbody>
 </table>
 