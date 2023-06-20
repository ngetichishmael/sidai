<?php

namespace App\Http\Controllers\Api\Total;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_price;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
   public function partialDelivery(Request $request, $delivery_code)
   {
      $validator = Validator::make($request, [
         '*.qty' => 'required',
         '*.productID' => 'required',
      ]);
      if ($validator->fails()) {
         return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors()
         ], 422);
      }
      $user_code = $request->user()->user_code;
      $requests = $request->collect();

      Delivery::where('delivery_code', $delivery_code)->update([
         'delivery_status' => "Partial delivery",
         "delivered_time" => now(),
         "customer_confirmation" => "partially delivered",
         "accept_allocation" => "partially delivered",
         "updated_by" => $user_code,
      ]);
      $total = 0;
      $order_code = Delivery::where('delivery_code', $delivery_code)->first();
      foreach ($requests as $value) {

         $delivery = Delivery_items::updateOrCreate(
            [
               "business_code" => $request->user()->business_code,
               "delivery_code" => $delivery_code,
               "productID" => $value["productID"],

            ],
            [
               "delivery_quantity" => $value["qty"],
               "item_condition" => $value["item_condition"],
               "note" => $value["note"],
               "created_by" => $user_code,
               "updated_by" => $user_code
            ]
         );
         $checker = Order_items::where('productID', $value["productID"])
            ->where('order_code', $order_code->order_code)->first();
         Order_items::where('productID', $value["productID"])
            ->where('order_code', $order_code->order_code)
            ->update([
               "delivery_quantity" => $value["qty"]
            ]);
         if ($checker->quantity > $value["qty"]) {
            Cart::updateOrCreate(
               [
                  'checkin_code' => $checker->checkin_code,
                  "order_code" => $order_code->order_code,
               ],
               [
                  'productID' => $value["productID"],
                  "product_name" => $checker->product_name,
                  "qty" => $checker->quantity - $value["qty"],
                  "price" => $checker->price,
                  "amount" => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
                  "total_amount" => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
                  "userID" => $user_code,
               ]
            );
            Orders::updateOrCreate(
               [

                  'order_code' => $order_code->order_code,
               ],
               [
                  'user_code' => $user_code,
                  'customerID' => $delivery->customer,
                  'price_total' => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
                  'balance' => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
                  'order_status' => 'Pending Delivery',
                  'payment_status' => 'Pending Payment',
                  'qty' => $value["qty"],
                  'checkin_code' => $checker->checkin_code,
                  'order_type' => 'Van sales',
                  'delivery_date' => now(),
                  'business_code' => $request->user()->business_code,
                  'updated_at' => now(),
               ]
            );
            Order_items::create([
               'order_code' => $order_code->order_code,
               'productID' => $value["productID"],
               'product_name' => $checker->product_name,
               'quantity' => $checker->quantity - $value["qty"],
               'sub_total' => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
               'total_amount' => ($checker->quantity - $value["qty"]) * $checker->ProductPrice->selling_price,
               'selling_price' => $checker->price,
               'discount' => 0,
               'taxrate' => 0,
               'taxvalue' => 0,
               'created_at' => now(),
               'updated_at' => now(),
            ]);
         }
         $total += product_price::whereId($value["productID"])->pluck('buying_price')->implode("") * $value["qty"];
      }
      return response()->json([
         "success" => true,
         "message" => "Partial delivery was successful",
         "total" => $total
      ]);
   }
   public function fullDelivery(Request $request, $delivery_code)
   {
      $user_code = $request->user()->user_code;
      $requests = $request->collect();

      Delivery::where('delivery_code', $delivery_code)->update([
         'delivery_status' => "DELIVERED",
         "delivered_time" => now(),
         "customer_confirmation" => "confirmed",
         "accept_allocation" => "accepted",
         "updated_by" => $user_code,
      ]);
      $total = 0;
      $order_code = Delivery::where('delivery_code', $delivery_code)->first();
      foreach ($requests as $value) {
         Delivery_items::updateOrCreate(
            [
               "business_code" => $request->user()->business_code,
               "delivery_code" => $delivery_code,
               "productID" => $value["productID"],

            ],
            [
               "delivery_quantity" => $value["qty"],
               "item_condition" => $value["item_condition"],
               "note" => $value["note"],
               "created_by" => $user_code,
               "updated_by" => $user_code
            ]
         );

         Order_items::where('productID', $value["productID"])
            ->where('order_code', $order_code->order_code)
            ->update([
               "delivery_quantity" => $value["qty"]
            ]);
         $total += product_price::whereId($value["productID"])->pluck('buying_price')->implode(" ") * $value["qty"];
      }
      return response()->json([
         "success" => true,
         "message" => "Delivery Successful",
         "total" => $total
      ]);
   }

   public function editDelivery(Request $request, $delivery_code)
   {
      $user_code = $request->user()->user_code;
      $requests = $request->collect();

      Delivery::where('delivery_code', $delivery_code)->update([
         'delivery_status' => "Partial delivery",
         "delivered_time" => now(),
      ]);
      $total = 0;
      $order_code = Delivery::where('delivery_code', $delivery_code)->first();
      foreach ($requests as $value) {
         Delivery_items::updateOrCreate(
            [
               "business_code" => $request->user()->business_code,
               "delivery_code" => $delivery_code,
               "productID" => $value["productID"],

            ],
            [
               "delivery_quantity" => $value["qty"],
               "created_by" => $user_code,
               "updated_by" => $user_code
            ]
         );
         Order_items::where('productID', $value["productID"])
            ->where('order_code', $order_code->order_code)
            ->update([
               "delivery_quantity" => $value["qty"]
            ]);
         $total += product_price::whereId($value["productID"])->pluck('buying_price')->implode(" ") * $value["qty"];
      }
      return response()->json([
         "success" => true,
         "message" => "Edit product successfully",
         "total" => $total,
      ]);
   }
   public function cancel(Request $request, $delivery_code)
   {
      Delivery::where('delivery_code', $delivery_code)->update(
         [
            "delivery_status" => "cancelled",
            "delivered_time" => now(),
            "customer_confirmation" => "cancelled",
            "accept_allocation" => "cancelled",
            'updated_by' => $request->user()->user_code,
         ]
      );

      $order_code = Delivery::where('delivery_code', $delivery_code)->first();
      Order_items::where('order_code', $order_code->order_code)
         ->update(["delivery_quantity" => "0"]);
      return response()->json([
         "success" => true,
         "message" => "Delivery Cancelled Successfully",
         "order_code" => $request->order_code,
      ]);
   }
}