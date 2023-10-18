<!DOCTYPE html>
<html>
<head>
    <title>Visits PDF Export</title>
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
            padding: 6px;
            text-align: left;
        }

        th {
            font-size: 11px; /* Adjust the font size as needed */
            background-color: #f2f2f2;
        }

        /* Add custom styling for the header */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .subheader {
            text-align: center;
            margin-bottom: 8px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px; /* Adjust the font size as needed */
        }


        /* Add any other custom CSS styles here */

    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('app-assets/images/logo.png'))) }}" alt="Logo" width="100" height="40">
        </div>
    </div>
    <div class="subheader">
       <p>
       @if ($timeInterval)
            @if ($timeInterval === 'today')
                Today Customers Checkins - {{ now()->format('F j, Y') }}
            @elseif ($timeInterval === 'yesterday')
                Yesterday Customers Checkins  - {{ now()->subDay()->format('F j, Y') }}
            @elseif ($timeInterval === 'this_week')
                This Week Customers Checkins 
            @elseif ($timeInterval === 'this_month')
                This Month Customers Checkins 
            @elseif ($timeInterval === 'this_year')
                This Year Customers Checkins 
            @else
                All Customers Checkins 
            @endif
        @else
            All Customers Checkins  
        @endif



           </p>
        </div>
    <hr>

    <div class="body">
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
                @foreach ($data as $visit)
                    <tr>
                        <td>{{ $visit->name ?? ''}}</td>
                        <td>{{ $visit->Customer->customer_name ?? ''}}</td>
                        <td>{{ $visit->ip ?? ''}}</td>
                        <td>{{ $visit->start_time ?? ''}}</td>
                        <td>{{ $visit->stop_time ?? ''}}</td>
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
                                    <span style="color: red; font-weight: bold;">Visit Active</span>
                                @endif
                            </td>

                            <td>{{ $visit->created_at->format('d-m-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<hr>
    <div class="footer">
        &copy; {{ date('Y') }} Kenmeat. All rights reserved.
    </div>
</body>
</html>
