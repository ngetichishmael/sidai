<div>
    <table>
       <thead>
       <tr>
        <th>Name of Distributor</th>
        <th>Orders Assigned</th>
        <th>Orders Fulfilled</th>
        <th>Status</th>
        <th>Fulfilment Rate</th>
       </tr>
       </thead>
       <tbody>
       @foreach ($distributors as $distributor)
          <tr>
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
     <div class="mt-4">
         <button wire:click="export" class="btn btn-primary">Export CSV</button>
     </div>
 </div>
 