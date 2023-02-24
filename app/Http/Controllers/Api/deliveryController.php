<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\customer\customers;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\Orders;
use Illuminate\Http\Request;

/**
 * @group Deliveries
 *
 * APIs to manage the product categories
 * */
class deliveryController extends Controller
{
   /**
    * User Delivery
    *
    * @param $businessCode
    * @param $userCode
    **/
   public function index($businessCode, $userCode)
   {
      $deliveries = Delivery::join('customers', 'customers.id', '=', 'delivery.customer')
         ->join('users', 'users.user_code', '=', 'delivery.allocated')
         ->where('delivery.business_code', $businessCode)
         ->where('delivery.allocated', $userCode)
         ->select('customer_name', 'name', 'delivery.created_at as delivery_date', 'delivery_status', 'order_code', 'delivery_code')
         ->orderBy('delivery.id', 'desc')
         ->get();

      return response()->json([
         "success" => true,
         "message" => "Delivery List",
         "data" => $deliveries
      ]);
   }

   /**
    * Delivery details
    *
    * @param $businessCode
    * @param $delivery_code
    **/
   public function details($delivery_code, $businessCode)
   {
      $delivery = Delivery::where('delivery_code', $delivery_code)->where('business_code', $businessCode)->first();
      $order = Orders::where('order_code', $delivery->order_code)->where('business_code', $businessCode)->first();
      $items = Delivery_items::join('product_information', 'product_information.id', '=', 'delivery_items.productID')
         ->where('delivery_code', $delivery_code)
         ->where('product_information.business_code', $businessCode)
         //                           ->select('allocated_quantity','item_condition')
         ->get();
      $customer = customers::where('id', $order->customerID)->first();


      return response()->json([
         "success" => true,
         "message" => "Delivery List",
         "delivery" => $delivery,
         "order"    => $order,
         "items"    => $items,
         "customer" => $customer,
      ]);
   }
   public function acceptDelivery(Request $request)
   {
      $data = $request->collect();

      foreach ($data as $value) {

         foreach ($value as $key) {
            Delivery::where('delivery_code', $key->delivery_code)->update([
               'delivery_status' => 'pending'
            ]);
         }
      }
      return response()->json(
         [
            "status" => 200,
            "success" => true,
            "message" => "Accepting deliveries...",
         ],
         200
      );
   }
   public function rejectDelivery(Request $request)
   {
      $data = $request->collect();
      foreach ($data as $key => $value) {
         info($value);
         Delivery::where('delivery_code', $value[0]["delivery_code"])->update([
            'delivery_status' => 'rejected',
            'note' => $value[$key]["note"]
         ]);
      }
      return response()->json(
         [
            "status" => 200,
            "success" => true,
            "message" => "Rejecting deliveries...",
         ],
         200
      );
   }
}
