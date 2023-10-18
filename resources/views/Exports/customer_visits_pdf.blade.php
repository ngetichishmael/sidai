<!DOCTYPE html>
<html>
<head>
   <title>Customers PDF Export</title>
   <style>
      body {
         font-family: Arial, sans-serif;
      }

      td {
         font-size: 10px; /* Adjust the font size as needed */
      }

      /* Add custom styling for the table */
      table {
         width: 100%;
         border-collapse: collapse;
      }

      th, td {
         border: 1px solid #ddd;
         padding: 8px;
         text-align: left;
      }

      th {
         font-size: 11px; /* Adjust the font size as needed */
         background-color: #f2f2f2;
      }

      /* Add custom styling for the header */
      .header {
         text-align: center;
         margin-bottom: 20px;
      }

      /* Add any other custom CSS styles here */

   </style>
</head>
<body>
<div class="header">
   <center>
      <div class="logo">
         <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('app-assets/images/logo.png'))) }}" alt="Logo" width="150" height="80">
      </div>
      <b>  <p>Customers Check-Ins</p> </b>
   </center>
</div>
<table>
   <thead>
   <tr>
      <th>Sales Agent</th>
      <th>Customer</th>
      <th>IP Address</th>
      <th>Start Time</th>
      <th>Stop Time</th>
      <th>Duration</th> <!-- New column for Duration -->
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
</body></html>
