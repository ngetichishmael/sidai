<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Controller;
use App\Models\customer\customers;
use App\Models\CustomerCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
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
}
