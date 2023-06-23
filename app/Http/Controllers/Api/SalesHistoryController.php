<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesHistoryController extends Controller
{
   public function index(Request $request, $shopID)
   {
      //$checking = checkin::where('customer_id', $shopID)->first();
      $user_code = $request->user()->user_code;
      //$customerID = $request->customer_id;

//      $query = DB::select('SELECT
//        `customerID`,
//        `user_code`,
//        `supplierID`,
//        `order_code`,
//        `price_total`,
//        `order_status`,
//        `payment_status`,
//        `checkin_code`,
//        `order_type`,
//        `created_at`
//    FROM
//        `orders` where `user_code`=? AND `customerID`=?', [$user_code, $shopID]);
      $query = DB::select('SELECT
        orders.customerID,
        orders.user_code,
        distributors.distributor_name,
        orders.order_code,
        orders.price_total,
        orders.order_status,
        orders.payment_status,
        orders.checkin_code,
        Customer.customer_name,
        orders.order_type,
        orders.created_at
    FROM
        orders
    INNER JOIN distributors ON orders.supplierID = distributors.id
    WHERE
        orders.user_code = ? AND orders.customerID = ?', [$user_code, $shopID]);


      return response()->json([
         "success" => true,
         "message" => "Sales / Van Sales",
         "Data" => $query
      ]);
   }
   public function vansales(Request $request, $shopID)
   {
      $user_code = $request->user()->user_code;

      $vansales = 'Van sales';
//      $query = DB::select(
//         'SELECT
//        *
//    FROM
//        `orders`  where `order_type`=?
//                  AND `user_code`=? AND `customerID`=?',
//         [$vansales, $user_code, $shopID]
//      );
      $query = Orders::with('distributor', 'Customer')
         ->where('order_type', $vansales)
         ->where('user_code', $user_code)
         ->where('customerID', $shopID)
         ->get();
      return response()->json([
         "success" => true,
         "message" => "Van Sales Order",
         "Data" => $query
      ]);
   }
   public function preorder($shopID)
   {
      $query = Orders::where("order_type", 'Pre Order')
         ->where('customerID', $shopID)->with('distributor', 'Customer')
         ->get();
      return response()->json([
         "success" => true,
         "message" => "New Sales Order",
         "Data" => $query
      ]);
   }
}
