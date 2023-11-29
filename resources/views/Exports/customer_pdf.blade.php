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
          <b>  <p>Customers Information</p> </b>
        </center>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th width="20%">Address</th>
                <th>Outlet</th>
                <th>Route/Town</th>
                <th>Subregion</th>
                <th>Region</th>
                <th>Status</th>
                <th>Added By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
                <tr>
                   <td>{{ $contact->customer_name ?? '' }}</td>
                   <td>{{ $contact->customer_number ?? ''}}</td>
                   <td>{{ $contact->customer_group ?? $contact->price_group }}</td>
                   <td> {!! $contact->area_name !!}</td>
                   <td>{!! $contact->subregion_name !!}</td>
                   <td>{!! $contact->region_name !!}</td>
                   <td>
                      @if ($contact->fstatus=="Active")
                         <span class="badge btn-outline-success">Active</span>
                      @elseif ($contact->fstatus=="Partially Inactive")
                         <span class="badge btn-outline-info">Partially Inactive</span>
                      @elseif ($contact->fstatus=="Inactive")
                         <span class="badge btn-outline-danger">Inactive</span>
                      @elseif ($contact->fstatus=="New")
                         <span class="badge btn-outline-secondary">New</span>
                      @elseif ($contact->fstatus=="New Inactive")
                         <span class="badge btn-outline-warning">New Inactive</span>
                      @else
                         <span class="badge btn-outline-warning">Inactive</span>
                      @endif
                   </td>
                   <td>{{ $contact->Creator->name ?? '' }}</td>
                    <td>{!! $contact->created_at ? $contact->created_at->format('Y-m-d h:i A') : '' !!} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
