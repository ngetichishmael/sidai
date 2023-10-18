<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Source</th>
            <th>Section</th>
            <th>User Name</th>
            <th>Activity</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($activities as $key => $activity)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $activity->source }}</td>
                <td>{{ $activity->section }}</td>
                <td>{{ $activity->user->name ?? 'NA' }}</td>
                <td>{{ $activity->activity ?? '' }}</td>
                <td>{{ $activity->created_at ?? now() }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center;"> No Record Found </td>
            </tr>
        @endforelse
    </tbody>
</table>
