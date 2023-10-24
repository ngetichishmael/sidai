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
      <b>  <p>Sidai System Users Activity Logs</p> </b>
   </center>
</div>
<table>
   <thead>
   <tr>
      <th>#</th>
      <th>Actvity</th>
      <th>User Name</th>
      <th>Section</th>
      <th>Activity</th>
      <th>Date</th>
   </tr>
   </thead>
   <tbody>
   @forelse ($activities as $key => $activity)
      <tr>
         <td>{{ $key + 1 }}</td>
         <td>{{ $activity->activity }}</td>
         <td>{{ $activity->user->name ?? 'NA' }}</td>
         <td>{{ $activity->section }}</td>
         <td>{{ $activity->action ?? '' }}</td>
         <td>{{ $activity->created_at ?? now() }}</td>
      </tr>
   @empty
      <tr>
         <td colspan="7" style="text-align: center;"> No Record Found </td>
      </tr>
   @endforelse
   </tbody>
</table>
</body>
</html>
