   <!-- Project table -->
   <div class="card mb-4">
       <h5 class="card-header">Customer Payments</h5>
       <div class="table-responsive mb-3">
           <table class="table datatable-project border-top">
               <thead>
                   <tr>
                       <th>Order Code</th>
                       <th>Amount</th>
                       <th>Balance</th>
                       <th>Payment Method</th>
                       <th>Date</th>
                   </tr>
               </thead>
               <tbody>
                   @foreach ($payments as $payment)
                       <tr>
                           <td>{{ $payment->order_id ?? ''}}</td>
                           <td>{{ $payment->amount ?? 0}}</td>
                           <td>{{ $payment->balance ?? 0}}</td>
                           <td>{{ $this->pluckLastPart($payment->payment_method ?? '') }}</td>
                           <td>{{ $payment->created_at->format('Y-m-d') ?? ''}}</td>
                       </tr>
                   @endforeach
           </table>
       </div>
   </div>
   <!-- /Project table -->
