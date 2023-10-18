<table>
   <thead>
      <tr>
         <th>Sales Agent</th>
         <th>Customer</th>
         <th>IP Address</th>
         <th>Start Time</th>
         <th>Stop Time</th>
         <th>Est. Duration</th>
         <th>Date</th>
      </tr>
   </thead>
   <tbody>
      @foreach($visits as $visit)
      <tr>
         <td>{{ $visit->User()->pluck('name')->implode('') }}</td>
         <td>{{ $visit->Customer()->pluck('customer_name')->implode('') }}</td>
         <td>{{ $visit->ip }}</td>
         <td>{{ $visit->start_time }}</td>
         <td>{{ $visit->stop_time }}</td>
         <td>
            @if (isset($visit->stop_time))
            @php
            $start = \Carbon\Carbon::parse($visit->start_time);
            $stop = \Carbon\Carbon::parse($visit->stop_time);
            $durationInSeconds = $start->diffInSeconds($stop);

            if ($durationInSeconds < 60) {
               echo $durationInSeconds . ' secs';
            } elseif ($durationInSeconds < 3600) {
               echo floor($durationInSeconds / 60) . ' mins';
            } else {
               echo floor($durationInSeconds / 3600) . ' hrs';
            }
            @endphp
            @else
            Visit Active
            @endif
         </td>
         <td>{{ $visit->created_at }}</td>
      </tr>
      @endforeach
   </tbody>
</table>
