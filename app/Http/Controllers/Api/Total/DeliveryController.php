<?php

namespace App\Http\Controllers\Api\Total;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\Delivery;
use App\Models\Order_items;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Delivery_items;
use App\Models\inventory\items;
use App\Http\Controllers\Controller;
use App\Models\inventory\allocations;
use App\Models\products\product_price;

use Illuminate\Support\Facades\Validator;
use App\Models\products\product_inventory;

class DeliveryController extends Controller
{
   public function partialDelivery(Request $request, $delivery_code)
   {
      $validator = Validator::make($request->all(), [
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

      $random = Str::random(20);
      $business_code = $request->user()->business_code;
      $user_code = $request->user()->user_code;
      $requests = $request->collect();
//      info('1');
      $delivery = Delivery::where('delivery_code', $delivery_code)->first();
      $order_code = $delivery->order_code;
//      info('2');
      $deliveryUpdates = [
         'delivery_status' => "Partial delivery",
         "delivered_time" => now(),
         "customer_confirmation" => "partially delivered",
         "accept_allocation" => "partially delivered",
         "updated_by" => $user_code,
      ];
//      info('3');
      Delivery::where('delivery_code', $delivery_code)->update($deliveryUpdates);

      $total = 0;
      $itemsToUpdate = [];
      $productIDs = [];
//      info('4');
      foreach ($requests as $value) {
         $productID = $value['productID'];
         $qty = $value['qty'];
//         info('5');
         $allocatedQty = Delivery_items::where('productID', $productID)
            ->where('delivery_code', $delivery_code)
            ->pluck('allocated_quantity')
            ->first();
//         info('6');
         $itemsToUpdate = [
            'product_code' => $productID,
            'created_by' => $user_code
         ];

         $itemsData = [
            'business_code' => $business_code,
            'allocation_code' => $random,
            'current_qty' => $allocatedQty,
            'allocated_qty' => $qty,
            'image' => $business_code,
            'returned_qty' => 0,
            'created_by' => $user_code,
            'updated_by' => $user_code
         ];
//         info($itemsToUpdate);
//         info("Updated");
//         info($itemsData);

         items::updateOrCreate(
            $itemsToUpdate,
            $itemsData
         );

         $productIDs[] = $productID;
         $total += product_price::whereId($productID)->pluck('buying_price')->first() * $qty;
      }
//      info('7');
      items::whereIn('product_code', $productIDs)->increment('allocated_qty', (int)$qty);
      product_inventory::whereIn('productID', $productIDs)->decrement('current_stock', (int) $qty);


      $allocationData = [
         "allocation_code" => $random,
         "sales_person" => $user_code,
         "business_code" => $business_code,
         "status" => "Waiting acceptance",
         "created_by" => $user_code,
         "updated_by" => $user_code
      ];
      allocations::updateOrCreate(["allocation_code" => $random], $allocationData);

      $deliveryItemsData = [
         "business_code" => $business_code,
         "delivery_code" => $delivery_code,
         "productID" => $productID,
         "delivery_quantity" => $qty,
         "item_condition" => $value["item_condition"],
         "note" => $value["note"],
         "created_by" => $user_code,
         "updated_by" => $user_code
      ];
      Delivery_items::updateOrCreate(["business_code" => $business_code, "delivery_code" => $delivery_code, "productID" => $productID], $deliveryItemsData);

      Order_items::where('productID', $productID)
         ->where('order_code', $order_code)
         ->update(["delivery_quantity" => $qty]);

      $checker = Order_items::where('productID', $productID)
         ->where('order_code', $order_code)
         ->first();

      if ($checker->quantity > $qty) {
         $cartData = [
            'checkin_code' => $checker->checkin_code,
            "order_code" => $order_code,
            'productID' => $productID,
            "product_name" => $checker->product_name,
            "qty" => $checker->quantity - $qty,
            "price" => $checker->price,
            "amount" => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            "total_amount" => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            "userID" => $user_code,
         ];
         Cart::updateOrCreate(['checkin_code' => $checker->checkin_code, "order_code" => $order_code], $cartData);

         Orders::updateOrCreate(['order_code' => $order_code], [
            'user_code' => $user_code,
            'customerID' => $delivery->customer,
            'price_total' => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            'balance' => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            'order_status' => 'Pending Delivery',
            'payment_status' => 'Pending Payment',
            'qty' => $qty,
            'checkin_code' => $checker->checkin_code,
            'order_type' => 'Van sales',
            'delivery_date' => now(),
            'business_code' => $business_code,
            'updated_at' => now(),
         ]);

         Order_items::create([
            'order_code' => $order_code,
            'productID' => $productID,
            'product_name' => $checker->product_name,
            'quantity' => $checker->quantity - $qty,
            'sub_total' => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            'total_amount' => ($checker->quantity - $qty) * $checker->ProductPrice->selling_price,
            'selling_price' => $checker->price,
            'discount' => 0,
            'taxrate' => 0,
            'taxvalue' => 0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);
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
