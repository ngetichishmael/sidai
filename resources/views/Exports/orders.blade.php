<table>
   <thead>
   <tr>
       <th>Distributor</th>
       <th>Customer</th>
       <th>Amount</th>
       <th>Delivery Status</th>
       <th>Payment Status</th>
       <th>Order Type</th>
       <th>Delivery Date</th>
   </tr>
   </thead>
   <tbody>
   @foreach($invoices as $invoice)
       <tr>
           <td>{{ $invoice->User()->pluck('name')->implode('') }}</td>
           <td>{{ $invoice->Customer()->pluck('customer_name')->implode('') }}</td>
           <td>{{ $invoice->price_total}}</td>
           <td>{{ $invoice->order_status}}</td>
           <td>{{ $invoice->payment_status}}</td>
           <td>{{ $invoice->order_type}}</td>
           <td>{{ $invoice->delivery_date}}</td>
       </tr>
   @endforeach
   </tbody>
</table>
