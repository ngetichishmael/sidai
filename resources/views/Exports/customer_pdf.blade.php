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
                <th>Number</th>
                <th width="20%">Address</th>
                <th>Zone/Region</th>
                <th>Route</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
                <tr>
                    <td>
                        {!! $contact->customer_name !!} <br>
                        @if ($contact->approval === 'Approved')
                        {{-- Add any custom content here if needed --}}
                        @endif
                    </td>
                    <td>{!! $contact->phone_number !!}</td>
                    <td>{{ $contact->address }} </td>
                    <td>
                        @if ($contact->Area && $contact->Area->Subregion && $contact->Area->Subregion->Region)
                            {!! $contact->Area->Subregion->Region->name !!}
                            @if ($contact->Area->Subregion->name)
                            , <br><i>{!! $contact->Area->Subregion->name !!}</i>
                            @endif
                        @endif
                    </td>
                    <td> {!! $contact->Area->name ?? '' !!}</td>
                   @php
                      $lastOrderDate = $contact->last_order_date ? \Carbon\Carbon::parse($contact->last_order_date) : null;
                                         $now = \Carbon\Carbon::now();
                                             if ($lastOrderDate !== null) {
                                                 $differenceInMonths = $lastOrderDate->diffInMonths($now);
                                             } else {
                                                 $differenceInMonths = null;
                                             }
                                         $threeMonthsAgo = \Carbon\Carbon::now()->subMonths(3);
                                         $oneMonthAgo = \Carbon\Carbon::now()->subMonth();
                                         $createdAtDate = \Carbon\Carbon::parse($contact->created_at);
                                          $daysDifference = $oneMonthAgo->diffInDays($createdAtDate);
                   @endphp

                   @if ($lastOrderDate != null && $lastOrderDate->lessThanOrEqualTo($oneMonthAgo))
                      <td><span class="badge btn-outline-success">Active</span></td>
                   @elseif ($lastOrderDate != null && ($daysDifference >= 30 && $daysDifference <= 90))
                      <td><span class="badge btn-outline-info">Partially Inactive</span></td>
                   @elseif ($lastOrderDate != null && $daysDifference >= 90)
                      <td><span class="badge btn-outline-danger">Inactive</span></td>
                   @elseif ($lastOrderDate === null && $daysDifference <= 30)
                      <td><span class="badge btn-outline-secondary"> New </span></td>
                   @elseif ($lastOrderDate === null && $daysDifference > 30)
                      <td><span class="badge btn-outline-warning"> New Inactive </span></td>
                   @else
                      <td><span class="badge btn-outline-warning"> Inactive </span></td>
                   @endif
                    <td>{!! $contact->Creator->name ?? '' !!} </td>
                    <td>{!! $contact->created_at ? $contact->created_at->format('Y-m-d h:i A') : '' !!} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
