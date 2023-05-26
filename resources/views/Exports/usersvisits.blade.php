<table class="table table-striped table-bordered">
    <thead>
        <th width="1%">#</th>
        <th>Sales Associate</th>
        <th>Visit Count</th>
        <th>Total Time Spent</th>
        <th>Total Trading Time</th>
    </thead>
    <tbody>
        @foreach ($visits as $count => $visit)
            <tr>
                <td>{!! $count + 1 !!}</td>
                <td>{!! $visit->name !!}</td>
                <td>{!! $visit->visit_count !!} </td>
                <td> {{ $visit->average_time }}</td>
                <td>{{ $visit->total_time_spent }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
