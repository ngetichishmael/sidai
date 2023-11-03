<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\order_payments;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// use Illuminate\Support\Str;
// use App\Models\order_payments as Payment;

class PaymentController extends Controller
{

   public function index(Request $request)
   {

      $user_code = $request->user()->user_code;
      $orderID = $request->get('orderID');
      $checking_code = DB::table('orders')->where('order_code', $orderID)->first();
      $amount = $request->get('amount');
      $transactionID = $request->get('transactionID');
      $paymentMethod = $request->get('paymentMethod');
      $balance = $checking_code->balance - $amount;
      $ID = $request->user()->id;
       // Check if the transactionID already exists
    $existingPayment = order_payments::where('reference_number', $transactionID)->first();
    if ($existingPayment) {
        return response()->json([
            "success" => false,
            "message" => "TransactionID already exists",
        ]);
    }

      order_payments::create([
         'amount' => $amount,
         'balance' => $balance,
         'payment_date' => now(),
         'payment_method' => $paymentMethod,
         'reference_number' => $transactionID,
         'order_id' => $orderID,
         'user_id' => $ID,
      ]);

      (string) $payment_status = $balance == 0 ? "PAID" : "PARTIAL PAID";

      Orders::where('order_code', '=', $orderID)
         ->update([
            'balance' => $balance,
            'payment_status' => $payment_status,
            'updated_at' => now()
         ]);
      DB::table('sales_targets')
         ->where('user_code', $user_code)
         ->increment('AchievedSalesTarget', $amount);
      $ativity_rand = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = 'Order Payment';
      $activityLog->user_code = $user_code ?? auth()->user()->user_code;
      $activityLog->section = 'Mobile';
      $activityLog->action = 'Sales order Payment made by' . Auth::user()->name . ' order code  ' . $orderID.' Status '.$payment_status ?? '';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $ativity_rand;
      $activityLog->ip_address = request()->ip();
      $activityLog->save();

      return response()->json([
         "success" => true,
         "message" => "Successfully",
         "Result" => $orderID

      ]);
   }
}
