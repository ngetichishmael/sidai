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
          <b>  <p>Distributors Information</p> </b>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name of Distributor</th>
                <th>Orders Assigned</th>
                <th>Orders Fulfilled</th>
                <th>Status</th>
                <th>Fulfilment Rate</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($distributors as $count=> $distributor)
                  <tr>
                    <td>{{ $count+1 }}</td>
                    <td>{{ $distributor->name}}</td>
                    <td>{{ $distributor->orders_count}}</td>
                    <td>{{ $distributor->orders_delivered_count}}</td>
                    <td>@if ($distributor->orders_delivered_count > 0 && ($distributor->orders_count - $distributor->orders_delivered_count) !=0 )
                        <p style="color: lightgreen">Fulfilled Partially</p>
                        @elseif ($distributor->orders_count >0 && ($distributor->orders_count - $distributor->orders_delivered_count)===0)
                        <p style="color: green">Hit</p>
                        @elseif (($distributor->orders_count - $distributor->orders_delivered_count)===$distributor->orders_count)
                        <p style="color: red">Missed</p>
                    @endif</td>
                    <td>
                        @if($distributor->orders_count > 0)
                            {{ number_format(($distributor->orders_delivered_count / $distributor->orders_count) * 100, 2) }}%
                        @else
                            N/A
                        @endif
                    </td>
                    
                 </tr>
                  @endforeach
        </tbody>
    </table>
</body>
</html>
