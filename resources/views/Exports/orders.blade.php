<table>
   <thead>
   <tr>
       {{-- <th>Distributor</th>
       <th>Customer</th>
       <th>Amount</th>
       <th>Delivery Status</th>
       <th>Payment Status</th>
       <th>Order Type</th>
       <th>Delivery Date</th> --}}

       <th width="1%">#</th>
        <th>Distributor</th>
        <th>Customer</th>
        <th>Sales Person</th>
        <th>Amount (Ksh.)</th>
        <th>Created Date</th>
       <th>Payment Status</th>
       <th>Order Type</th>
       <th>Delivery Date</th>
        <th>Status</th>
   </tr>
   </thead>
   <tbody>
   @foreach($invoices as $count=>$invoice)
   <tr>
           <td>{{ $count + 1}}</td>
           <td>{{ $invoice->distributor()->pluck('name')->implode('') }}</td>
           <td>{{ $invoice->order->Customer->customer_name??'N/A'}}</td>
           <td>{{ $invoice->User->name??'N/A'}}</td>
           <td>{{ $invoice->price_total}}</td>
           <td>{{ $invoice->created_at}}</td>
           <td>{{ $invoice->payment_status}}</td>
           <td>{{ $invoice->order_type}}</td>
           <td>{{ $invoice->delivery_date}}</td>
           <td>{{ $invoice->order_status}}</td>
           
       </tr>
   @endforeach
   </tbody>
</table>
