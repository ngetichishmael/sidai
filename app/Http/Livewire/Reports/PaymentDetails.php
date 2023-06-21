<?php

namespace App\Http\Livewire\Reports;

use App\Models\order_payments;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PaymentDetails extends Component
{
   public $order_code;
   public function render()
   {
      $order_details = order_payments::where('order_id', $this->order_code)->get();
      $order_details = DB::table('order_items as oi')
         ->join('orders as o', 'oi.order_code', '=', 'o.order_code')
         ->join('order_payments as op', 'o.order_code', '=', 'op.order_id')
         ->where('oi.order_code', '=', $this->order_code)
         ->select(
            'oi.product_name',
            'o.order_code',
            'op.payment_method',
            'op.amount',
            'oi.requested_quantity',
            'oi.delivery_quantity'
         )
         ->get();

      return view('livewire.reports.payment-details', [
         'order_details' => $order_details
      ]);
   }
   public function pluckLastPart($string )
   {
      $prefix = "PaymentMethods.";
      if (strpos($string, $prefix) === 0) {
         return substr($string, strlen($prefix));
      } else {
         return $string;
      }
   }
}
