<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\customer\customers;
use App\Models\CustomerCart;
use App\Models\Delivery;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use App\Models\products\product_price;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
   public function isCreditor(Request $request){
      $customer = customers::find($request->customer_id);
      if (!empty($customer)){
         customers::whereId($request->id)->update([ 'is_creditor'=>$request->is_creditor]);
         return response()->json([
            "success" => true,
            "message" => "Customer Creditor status updated successfully",
         ], 200);
      }

      return response()->json([
         "success" => false,
         "message" => "Customer Not found"
      ], 409);
   }

   public function addToCart(Request $request)
   {

      $checker = CustomerCart::where('user_code', $request->user()->user_code)
         ->where('product_id', $request->product_id)->first();

      if ($checker == null) {
         CustomerCart::updateOrcreate(
            [
               "user_code" => $request->user()->user_code,
               "product_id" => $request->product_id,
               "quantity" => $request->quantity,
            ]
         );
      } else {
         DB::table('customer_carts')
            ->where('user_code', $request->user()->user_code)
            ->where('product_id', $request->product_id)
            ->increment('quantity', $request->quantity);
      }

      return response()->json([
         "success" => true,
         "message" => "Product added to cart successfully",
         "data" => CustomerCart::where("user_code", $request->user()->user_code)
            ->where('product_id', $request->product_id)
            ->first(),
      ]);
   }
   public function getCartItems(Request $request)
   {
      return response()->json([
         "success" => true,
         "message" => "Get all cart items",
         "data" => CustomerCart::with("ProductInformation.ProductPrice")->where("user_code", $request->user()->user_code)->get(),
      ]);
   }
   public function deleteFromCart(Request $request)
   {
      DB::table('customer_carts')
         ->where('user_code', $request->user()->user_code)
         ->where('product_id', $request->product_id)
         ->decrement('quantity', $request->quantity);
      $quantity = CustomerCart::where("user_code", $request->user()->user_code)
         ->where('product_id', $request->product_id)
         ->pluck('quantity');
      if ($quantity[0] <= 0) {
         CustomerCart::where("user_code", $request->user()->user_code)
            ->where('product_id', $request->product_id)
            ->delete();
      }

      return response()->json([
         "success" => true,
         "message" => "Product removed successfully",
      ]);
   }
   public function clearCart(Request $request)
   {
      return response()->json([
         "success" => true,
         "message" => "Product removed successfully",
         "status" => CustomerCart::where("user_code", $request->user()->user_code)->delete(),

      ]);
   }

   public function checkOut(Request $request)
   {
      $order_code = Str::random(20);
      $checkin_code = Str::random(20);
//      $user_code = Str::random(20);
      $user_code = $request->user()->user_code;
      $products = CustomerCart::where("user_code", $request->user()->user_code)->get();
      $customer = \App\Models\customers::where("user_code", $request->user()->user_code)->first();
      if(empty($products) || empty($customer)){
         return response()->json([
            "success" => false,
            "status" => 401,
            "Message" => "No products in your cart, please add to checkout",
         ]);
      }

      foreach ($products as $key => $product) {
         $pricing = product_price::whereId($product->product_id)->first();
         $product_information = product_information::with('ProductPrice')->whereId($product->product_id)->first();
//                if ($key > 0){
//                   return response()->json([$product_information]);
//                }

         Cart::updateOrCreate(
            [
               'checkin_code' => $checkin_code,
               "order_code" => $order_code,
            ],
            [
               'productID' => $product->product_id,
               "product_name" => $product_information->product_name,
               "qty" =>  $product->quantity,
               "price" => $product_information->ProductPrice->selling_price,
               "amount" =>  $product_information->quantity * $product_information->ProductPrice->selling_price,
               "total_amount" =>  $product->quantity * $product_information->ProductPrice->selling_price,
               "userID" => $user_code,
            ]
         );
         Orders::updateOrCreate(
            [

               'order_code' => $order_code,
            ],
            [
               'user_code' => $user_code,
               'customerID' => $customer->id,
               "price_total" => $pricing->buying_price * $product->quantity,
               "balance" => $pricing->buying_price * $product->quantity,
               'order_status' => 'Pending Delivery',
               'payment_status' => 'Pending Payment',
               'qty' => $product->quantity,
               'checkin_code' => $checkin_code,
               'order_type' => 'Pre Order',
               'delivery_date' => now(),
               'business_code' => $request->user()->business_code,
               'created_at' => now()
            ]
         );
         Order_items::create([
            'order_code' => $order_code,
            'productID' => $product->product_id,
            'product_name' => $product_information->product_name,
            'quantity' => $product->quantity,
            'sub_total' => $product_information->ProductPrice->buying_price,
            'total_amount' => $product->quantity * $pricing->buying_price,
            'selling_price' => $pricing->buying_price,
            'discount' => 0,
            'taxrate' => 0,
            'taxvalue' => 0,
         ]);
      }
      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "Checkout successiful"
      ]);
   }

   public function getAllCustomerPendingDeliveries(Request $request)
   {
      $customer_code = $request->user()->user_code;
      $code=customers::where('user_code',$customer_code)->first();
      $id=$code->id;
      $deliveries = Orders::with(['OrderItem'=> function ($query) {
         $query->select('id', 'order_code', 'quantity', 'productID')
            ->with([
               'productInformation' => function ($query) {
                  $query->select('id', 'product_name','sku_code', 'brand', 'supplierID','short_description', 'notification_email', 'url', 'description', 'category','image', 'business_code', 'status', 'active', 'created_at');
               },
               'productPrice' => function ($query) {
                  $query->select('id', 'productID', 'buying_price', 'selling_price', 'created_at');
               }
            ]);
      }])
         ->where('customerID', $id)->where('order_status', 'Pending Delivery')
         ->get();
      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Pending Deliveries with their Delivery Items, products and price",
         "pending-deliveries" => $deliveries,
      ]);
   }
   public function getAllCustomerDeliveries(Request $request)
   {
      $customer_code = $request->user()->user_code;
      $code=customers::where('user_code',$customer_code)->first();
      $id=$code->id;
      $deliveries = Orders::with(['OrderItems'=> function ($query) {
         $query->select('id', 'order_code', 'quantity', 'requested_quantity','requested_allocated','productID')
            ->with([
               'productInformation' => function ($query) {
                  $query->select('id', 'product_name', 'brand', 'supplierID','short_description', 'notification_email', 'url', 'description', 'category','image', 'business_code', 'status', 'active', 'created_at');
               },
               'productPrice' => function ($query) {
                  $query->select('id', 'productID', 'buying_price', 'selling_price','distributor_price', 'created_at');
               }
            ]);
      }])
         ->where('customerID', $id)
         ->get();

      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Deliveries with their Delivery Items, products and price",
         "deliveries" => $deliveries,
      ]);
   }
   public function getAllCustomerDeliveries2(Request $request)
   {
      $customer_code = $request->user()->user_code;
      $code=customers::where('user_code',$customer_code)->first();
      $id=$code->id;
      $deliveries = Delivery::with(['Order',
         'OrderItems'=> function ($query) {
            $query->select('id', 'order_code', 'quantity', 'productID')
               ->with([
                  'productInformation' => function ($query) {
                     $query->select('id', 'product_name','sku_code', 'brand', 'supplierID','short_description', 'notification_email', 'url', 'description', 'category','image', 'business_code', 'status', 'active', 'created_at');
                  },
                  'productPrice' => function ($query) {
                     $query->select('id', 'productID', 'buying_price', 'selling_price', 'created_at');
                  }
               ]);
         }
      ])
         ->where('customer', $id)
         ->get();
      $deliveries = new Collection($deliveries);
      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Deliveries with their Delivery Items and orders",
         "deliveries" => $deliveries,
      ]);
   }

   public function updateFcmToken(Request $request){
      $user_code=$request->user()->user_code;
      User::where('user_code', $user_code)->update([
         'fcm_token'=>$request->fmc_token
      ]);
   }
}
