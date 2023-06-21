<?php

namespace App\Http\Livewire\Reports;

use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Payments extends Component
{
   public function render()
   {


      $orders = Orders::select(
         'orders.id',
         'customers.customer_name',
         'orders.order_code',
         'customers.customer_type',
         'orders.created_at',
         DB::raw('COALESCE(SUM(order_payments.amount), 0) AS total_payment')
      )
         ->join('customers', 'orders.customerID', '=', 'customers.id')
         ->leftJoin('order_payments', 'orders.order_code', '=', 'order_payments.order_id')
         ->get();
      return view('livewire.reports.payments', [
         'orders' => $orders
      ]);
   }
}
