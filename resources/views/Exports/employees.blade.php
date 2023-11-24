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
          <b>  <p>Employees Information</p> </b>
        </center>
    </div>
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
            @foreach ($employees as $count=> $employee)
                  <tr>
                    <td>{{ $count+1 }}</td>
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
</body>
</html>
